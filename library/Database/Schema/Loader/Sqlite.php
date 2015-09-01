<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Schema\Loader;

use ReflectionClass;
use Slick\Common\Utils\Text;
use Slick\Database\RecordList;
use Slick\Database\Schema\LoaderInterface;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;

/**
 * Sqlite Schema loader
 *
 * @package Slick\Database\Schema\Loader
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Sqlite extends Standard implements LoaderInterface
{

    /**
     * @read
     * @var string
     */
    protected $getTablesSql = <<<EOQ
SELECT * FROM sqlite_master
WHERE type='table' AND name != 'sqlite_sequence'
EOQ;

    /**
     * @read
     * @var string
     */
    protected $getColumnsSql = "PRAGMA table_info(%s)";

    /**
     * @read
     * @var array
     */
    protected $typeExpressions = [
        self::COLUMN_BLOB      => '(BINARY)|(VARBINARY)|(NONE)',
        self::COLUMN_BOOLEAN   => '(BOOLEAN)|(BOOL)|(BIT)',
        self::COLUMN_INTEGER   => '(INT)|(SERIAL)|(INTEGER)|(YEAR)',
        self::COLUMN_DATE_TIME => '(DATE)|(TIME)',
        self::COLUMN_DECIMAL   => '(DOUBLE)|(NUMERIC)|(REAL)',
        self::COLUMN_TEXT      => '(TEXT)|(ENUM)',
        self::COLUMN_VARCHAR   => '(VARCHAR)|(CHAR)'
    ];

    /**
     * Returns a list of table names
     *
     * @return string[]
     */
    public function getTables()
    {
        if (is_null($this->tables)) {
            $result = $this->adapter->query($this->getTablesSql);
            $names = [];
            foreach ($result as $table) {
                $names[] = $table['name'];
            }
            $this->tables = $names;
        }
        return $this->tables;
    }

    /**
     * Retrieve the column metadata from a given table
     *
     * @param string $tableName
     * @return \Slick\Database\RecordList
     */
    protected function getColumns($tableName)
    {
        return $this->adapter->query(
            sprintf($this->getColumnsSql, $tableName)
        );
    }

    /**
     * Crates a DDL column object for provided column metadata
     *
     * @param array $colData
     *
     * @return ColumnInterface
     */
    protected function createColumn($colData)
    {
        $nameSpace = 'Slick\Database\Sql\Ddl\Column';
        $type = $this->getColumnClass($colData['type']);
        $reflection = new ReflectionClass($nameSpace."\\{$type}");
        $column = $reflection->newInstanceArgs(
            [
                $colData['name'],
                [
                    'nullable' => (!(boolean) $colData['notnull']),
                    'default' => $colData['dflt_value']
                ]
            ]
        );
        return $column;
    }

    /**
     * Returns the constraints of the provided table
     *
     * @param string $tableName
     *
     * @return \Slick\Database\RecordList
     */
    protected function getConstraints($tableName)
    {
        $structure = [
            'constraintType' => null,
            'constraintName' => null,
            'columnName' => null,
            'referenceTable' => null,
            'referenceColumn' => null,
            'onUpdate' => null,
            'onDelete' => null
        ];
        $data = [];
        $columns = $this->adapter->query("PRAGMA table_info({$tableName})");
        foreach ($columns as $col) {
            if ($col['pk'] == 1) {
                $data[] = array_merge(
                    $structure,
                    [
                        'constraintType' => 'PRIMARY KEY',
                        'constraintName' => $tableName.'Primary',
                        'columnName' => $col['name']
                    ]
                );
            }
        }
        $sql = "PRAGMA index_list({$tableName})";
        $indexes = $this->adapter->query($sql);
        foreach ($indexes as $index) {
            if ($index['unique'] == 1) {
                $info = $this->adapter->query(
                    "PRAGMA index_info({$index['name']})"
                );
                $data[] = array_merge(
                    $structure,
                    [
                        'constraintType' => 'UNIQUE',
                        'constraintName' => $index['name'],
                        'columnName' => $info[0]['name']
                    ]
                );
            }
        }
        $foreignKeys = $this->adapter
            ->query("PRAGMA foreign_key_list({$tableName})");
        foreach ($foreignKeys as $frk) {
            $data[] = $this->createFkData($frk, $tableName);
        }
        return new RecordList(['data' => $data]);
    }

    /**
     * Creates a known structure for foreign key constraint creation
     *
     * @param array $data
     * @param string $table
     * @return array
     */
    private function createFkData($data, $table)
    {
        $name = $table.ucfirst(Text::singular($data['table'])).'Fk';
        $structure = [
            'constraintType' => 'FOREIGN KEY',
            'constraintName' => $name,
            'columnName' => $data['from'],
            'referenceTable' => $data['table'],
            'referenceColumn' => $data['to'],
            'onUpdate' => $data['on_update'],
            'onDelete' => $data['on_delete']
        ];
        return $structure;
    }
}
