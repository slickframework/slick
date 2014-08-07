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
use Slick\Database\Schema;
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
 * @property-read array            $typeExpressions
 * @property-read array            $constraintExpressions
 *
 * @method Standard setDefaultColumn($column) Sets default column class
 * @method void     getDefaultColumn() Returns current default column class
 * @method array    getTypeExpressions() Returns the regex used in column
 *                                       instantiation.
 * @method array    getConstraintExpressions() Returns the regex used in
 *                                             constraint instantiation.
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

    /**#@+
     * Supported constraints
     * @var string
     */
    const CONSTRAINT_PRIMARY     = 'Primary';
    const CONSTRAINT_UNIQUE      = 'Unique';
    const CONSTRAINT_FOREIGN_KEY = 'ForeignKey';
    /**@#-*/

    /**
     * @read
     * @var string
     */
    protected $_getTablesSql = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA=?";

    /**
     * @read
     * @var string
     */
    protected $_getColumnsSql = "SELECT
                  COLUMN_NAME AS columnName,
                  data_type AS type,
                  character_maximum_length AS length,
                  numeric_precision AS 'precision',
                  column_default AS 'default',
                  is_nullable AS isNullable
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

    /**
     * @read
     * @var string
     */
    protected $_getConstraintsSql = "SELECT
                  tc.CONSTRAINT_NAME AS constraintName,
                  CONSTRAINT_TYPE AS constraintType,
                  rc.UPDATE_RULE AS onUpdate,
                  rc.DELETE_RULE AS onDelete,
                  ccu.COLUMN_NAME AS columnName,
                  rccu.COLUMN_NAME AS referenceColumn,
                  rccu.TABLE_CATALOG,
                  rccu.TABLE_SCHEMA,
                  rccu.TABLE_NAME AS referenceTable,
                  CHECK_CLAUSE AS checkClause
                FROM
                  INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
                  LEFT JOIN
                  INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE ccu ON
                    tc.CONSTRAINT_CATALOG=ccu.CONSTRAINT_CATALOG AND
                    tc.CONSTRAINT_SCHEMA=ccu.CONSTRAINT_SCHEMA AND
                    tc.CONSTRAINT_NAME=ccu.CONSTRAINT_NAME AND
                    tc.TABLE_CATALOG=ccu.TABLE_CATALOG AND
                    tc.TABLE_SCHEMA=ccu.TABLE_SCHEMA AND
                    tc.TABLE_NAME=ccu.TABLE_NAME
                  LEFT JOIN
                  INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc ON
                    rc.CONSTRAINT_CATALOG=ccu.CONSTRAINT_CATALOG AND
                    rc.CONSTRAINT_SCHEMA=ccu.CONSTRAINT_SCHEMA AND
                    rc.CONSTRAINT_NAME=ccu.CONSTRAINT_NAME
                  LEFT JOIN
                  INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE rccu ON
                    rc.UNIQUE_CONSTRAINT_CATALOG=rccu.CONSTRAINT_CATALOG AND
                    rc.UNIQUE_CONSTRAINT_SCHEMA=rccu.CONSTRAINT_SCHEMA AND
                    rc.UNIQUE_CONSTRAINT_NAME=rccu.CONSTRAINT_NAME
                  LEFT JOIN
                  INFORMATION_SCHEMA.CHECK_CONSTRAINTS cc ON
                    tc.CONSTRAINT_CATALOG=cc.CONSTRAINT_CATALOG AND
                    tc.CONSTRAINT_SCHEMA=cc.CONSTRAINT_SCHEMA AND
                    tc.CONSTRAINT_NAME=cc.CONSTRAINT_NAME
                WHERE
                  tc.TABLE_SCHEMA=:schemaName AND   -- see remark
                  tc.TABLE_NAME=:tableName
                ORDER BY tc.CONSTRAINT_NAME";

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
        self::COLUMN_BLOB => '(BINARY)|(VARBINARY)|(BLOB)',
        self::COLUMN_BOOLEAN => '(BOOLEAN)|(BOOL)|(BIT)',
        self::COLUMN_INTEGER => '(INT)|(SERIAL)|(INTEGER)|(YEAR)',
        self::COLUMN_DATE_TIME => '(DATE)|(TIME)',
        self::COLUMN_FLOAT => '(DECIMAL)|(FLOAT)|(DEC)|(DOUBLE)|(NUMERIC)',
        self::COLUMN_TEXT => '(TEXT)|(ENUM)',
        self::COLUMN_VARCHAR => '(VARCHAR)|(CHAR)'
    ];

    /**
     * @read
     * @var array
     */
    protected $_constraintExpressions = [
        self::CONSTRAINT_FOREIGN_KEY => '(FOREIGN)',
        self::CONSTRAINT_PRIMARY => '(PRIMARY)',
        self::CONSTRAINT_UNIQUE => '(UNIQUE)'
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

            $result = $this->_adapter->query(
                $this->_getTablesSql,
                [$this->_adapter->getSchemaName()]
            );

            $names = [];
            foreach ($result as $table) {
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

        $constraints = $this->_getConstraints($tableName);
        foreach ($constraints as $constraint) {
            $object = $this->_createConstraint($constraint);
            if ($object) {
                $table->addConstraint($object);
            }
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
        $schema = new Schema(['adapter' => $this->_adapter]);
        $tables = $this->getTables();
        foreach ($tables as $tableName) {
            $schema->addTable($this->getTable($tableName));
        }

        return $schema;
    }

    /**
     * Retrieve the column metadata from a given table
     *
     * @param string $tableName
     * @return \Slick\Database\RecordList
     */
    protected function _getColumns($tableName)
    {

        $params = [
            ':schemaName' => $this->_adapter->getSchemaName(),
            ':tableName' => $tableName
        ];
        return $this->_adapter->query($this->_getColumnsSql, $params);
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
     * Crates a DDL column object for provided column metadata
     *
     * @param array $colData
     *
     * @return ColumnInterface
     */
    protected function _createColumn($colData)
    {
        $nameSpace = 'Slick\Database\Sql\Ddl\Column';
        $type = $this->_getColumnClass($colData['type']);
        $reflection = new ReflectionClass($nameSpace . "\\{$type}");

        switch ($type) {

            case self::COLUMN_BOOLEAN:
                $column = $reflection->newInstanceArgs(
                    [$colData['columnName']]
                );
                break;

            case self::COLUMN_BLOB:
            case self::COLUMN_TEXT:
            case self::COLUMN_INTEGER:
            case self::COLUMN_DATE_TIME:
                $column = $reflection->newInstanceArgs(
                    [
                        $colData['columnName'],
                        [
                            'length' => $colData['length'],
                            'nullable' => (boolean) $colData['isNullable'],
                            'default' => $colData['default']
                        ]
                    ]
                );
                break;
            default:
            case self::COLUMN_VARCHAR:
                $column = $reflection->newInstanceArgs(
                    [
                        $colData['columnName'],
                        $colData['length']
                    ]
                );
        }

        return $column;
    }

    /**
     * Returns the constraints of the provided table
     *
     * @param string $tableName
     *
     * @return \Slick\Database\RecordList
     */
    protected function _getConstraints($tableName)
    {

        $params = [
            ':schemaName' => $this->_adapter->getSchemaName(),
            ':tableName' => $tableName
        ];

        return $this->_adapter->query($this->_getConstraintsSql, $params);
    }

    /**
     * Retrieves the constraint class name for provided constraint type
     *
     * @param string $type
     *
     * @return null|string
     */
    protected function _getConstraintClass($type)
    {
        $class = null;
        foreach ($this->_constraintExpressions as $className => $exp) {
            if (preg_match("/{$exp}/i", $type)) {
                $class = $className;
                break;
            }
        }
        return $class;
    }

    protected function _createConstraint($constraintData)
    {
        $nameSpace = 'Slick\Database\Sql\Ddl\Constraint';
        $type = $this->_getConstraintClass($constraintData['constraintType']);
        if (is_null($type)) { // Unknown/Unsupported constraint
            return false;
        }
        $reflection = new ReflectionClass($nameSpace."\\".$type);

        $constraint = null;
        switch ($type) {
            case self::CONSTRAINT_FOREIGN_KEY:
                $constraint = $reflection->newInstanceArgs(
                    [
                        $constraintData['constraintName'],
                        $constraintData['columnName'],
                        $constraintData['referenceTable'],
                        $constraintData['referenceColumn'],
                        [
                            'onUpdate' => $constraintData['onUpdate'],
                            'onDelete' => $constraintData['onDelete'],
                        ]
                    ]
                );
                break;

            case self::CONSTRAINT_PRIMARY:
                $constraint = $reflection->newInstanceArgs(
                    [
                        $constraintData['constraintName'],
                        [
                            $constraintData['columnName']
                        ]
                    ]
                );
                break;

            case self::CONSTRAINT_UNIQUE:
                $constraint = $reflection->newInstanceArgs(
                    [$constraintData['constraintName']]
                );
                break;
        }

        return $constraint;
    }
}