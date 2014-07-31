<?php

/**
 * Standard Schema loader
 *
 * @package   Slick\Database\Schema\Loader
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Schema\Loader;

use ReflectionClass;
use Slick\Common\BaseMethods;
use Slick\Database\Schema\Table;
use Slick\Database\Schema\LoaderInterface;
use Slick\Database\Schema\SchemaInterface;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;

/**
 * Standard Schema loader
 *
 * @package   Slick\Database\Schema\Loader
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property      AdapterInterface $adapter
 * @property      string           $defaultColumn
 * @property-read string[]         $tables
 *
 * @method Standard setDefaultColumn($column) Sets default column class
 * @method void     getDefaultColumn() Returns current default column class
 * @method array    getTypeExpressions() Returns the regex used in column
 *                                       instantiation.
 *
 */
class Standard implements LoaderInterface
{

    /**#@+
     * Supported column types
     * @var string
     */
    const COLUMN_BLOB      = 'Blob';
    const COLUMN_BOOLEAN   = 'Boolean';
    const COLUMN_DATE_TIME = 'DateTime';
    const COLUMN_FLOAT     = 'Float';
    const COLUMN_INTEGER   = 'Integer';
    const COLUMN_TEXT      = 'Text';
    const COLUMN_VARCHAR   = 'Varchar';
    /**#@-*/

    /**
     * Factory behavior methods from Slick\Common\Base class
     */
    use BaseMethods;

    /**
     * @readwrite
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * @read
     * @var string[]
     */
    protected $_tables;

    /**
     * @read
     * @var array
     */
    protected $_typeExpressions = [
        self::COLUMN_BLOB => '(BINARY)|(VARBINARY)|(INTEGER)',
        self::COLUMN_BOOLEAN => '(BOOLEAN)|(BOOL)|(BIT)',
        self::COLUMN_INTEGER => '(INT)|(SERIAL)|(INTEGER)|(YEAR)',
        self::COLUMN_DATE_TIME => '(DATE)|(TIME)',
        self::COLUMN_FLOAT => '(DECIMAL)|(FLOAT)|(DEC)|(DOUBLE)|(NUMERIC)',
        self::COLUMN_TEXT => '(TEXT)|(ENUM)',
        self::COLUMN_VARCHAR => '(VARCHAR)|(CHAR)'
    ];

    /**
     * @readwrite
     * @var string
     */
    protected $_defaultColumn = self::COLUMN_VARCHAR;

    /**
     * Easy construction with base methods
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->_createObject($options);
    }

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return Standard
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Returns a list of table names
     *
     * @return string[]
     */
    public function getTables()
    {
        if (is_null($this->_tables)) {
            $sql  = "SELECT * FROM INFORMATION_SCHEMA.TABLES ";
            $sql .= "WHERE TABLE_TYPE='BASE TABLE' ";
            $sql .= "AND TABLE_SCHEMA=?";

            $result = $this->_adapter->query(
                $sql,
                [$this->_adapter->getSchemaName()]
            );

            $names = [];
            foreach ($result as $table)
            {
                $names[] = $table['TABLE_NAME'];
            }
            $this->_tables = $names;
        }
        return $this->_tables;
    }

    /**
     * Retrieves a table object for provided table name
     * @param string $tableName
     *
     * @return Table
     */
    public function getTable($tableName)
    {
        $table = new Table($tableName);
        $columns = $this->_getColumns($tableName);

        foreach ($columns as $col) {
            $table->addColumn($this->_createColumn($col));
        }

        return $table;
    }

    /**
     * Returns the schema for the given interface
     *
     * @return SchemaInterface
     */
    public function getSchema()
    {
        // TODO: Implement getSchema() method.
    }

    /**
     * Retrieve the column metadata from a given table
     *
     * @param string $tableName
     * @return \Slick\Database\RecordList
     */
    protected function _getColumns($tableName)
    {
        $sql = "SELECT
                  column_name,
                  data_type,
                  character_maximum_length,
                  numeric_precision,
                  column_default,
                  is_nullable
                FROM
                  information_schema.tables as t
                  JOIN
                  information_schema.columns AS c ON
                    t.table_catalog=c.table_catalog AND
                    t.table_schema=c.table_schema AND
                    t.table_name=c.table_name
                WHERE
                    c.table_schema=:schemaName
                  AND
                    c.table_name=:tableName";
        $params = [
            ':schemaName' => $this->_adapter->getSchemaName(),
            ':tableName' => $tableName
        ];
        return $this->_adapter->query($sql, $params);
    }

    /**
     * Returns the column class name for a given column type
     *
     * @param string $typeName
     *
     * @return string
     */
    protected function _getColumnClass($typeName)
    {
        $class = $this->_defaultColumn;
        foreach ($this->_typeExpressions as $className => $exp) {
            if (preg_match("/{$exp}/i", $typeName)) {
                $class = $className;
                break;
            }
        }
        return $class;
    }

    /**
     * @param $colData
     * @return ColumnInterface
     */
    protected function _createColumn($colData)
    {
        $nameSpace = 'Slick\Database\Sql\Ddl\Column';
        $type = $this->_getColumnClass($colData['data_type']);
        $reflection = new ReflectionClass($nameSpace . "\\{$type}");

        switch ($type) {

            case self::COLUMN_BOOLEAN:
                $column = $reflection->newInstanceArgs([
                    $colData['column_name']
                ]);
                break;

            case self::COLUMN_BLOB:
            case self::COLUMN_TEXT:
            case self::COLUMN_INTEGER:
            case self::COLUMN_DATE_TIME:
                $column = $reflection->newInstanceArgs([
                    $colData['column_name'],
                    $colData['character_maximum_length'],
                    [
                        'nullable' => (boolean) $colData['is_nullable'],
                        'default' => $colData['column_default']
                    ]
                ]);
                break;
            default:
            case self::COLUMN_VARCHAR:
                $column = $reflection->newInstanceArgs([
                    $colData['column_name'],
                    $colData['character_maximum_length']
                ]);
                break;
        }

        return $column;
    }
}