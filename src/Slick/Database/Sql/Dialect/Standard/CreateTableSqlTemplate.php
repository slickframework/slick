<?php

/**
 * Standard Create Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Create Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
{

    /**
     * @var array
     */
    protected static $_columnTypes = [
        'Slick\Database\Sql\Ddl\Column\Integer' => '_getIntegerColumn',
        'Slick\Database\Sql\Ddl\Column\Text' => '_getTextColumn',
        'Slick\Database\Sql\Ddl\Column\Varchar' => '_getVarcharColumn',
        'Slick\Database\Sql\Ddl\Column\Boolean' => '_getBooleanColumn',
        'Slick\Database\Sql\Ddl\Column\Float' => '_getFloatColumn',
        'Slick\Database\Sql\Ddl\Column\DateTime' => '_getDateTimeColumn',
        'Slick\Database\Sql\Ddl\Column\Blob' => '_getBlobColumn'
    ];

    /**
     * @var array
     */
    protected static $_textSizeInBytes = [
        Column\Size::SMALL => 255,
        Column\Size::TINY => 255,
        Column\Size::NORMAL => 65532,
        Column\Size::MEDIUM => 16000,
        Column\Size::LONG => 4000000,
        Column\Size::BIG => 4000000,
    ];

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        /** @var CreateTable $sql */
        $this->_sql = $sql;
        $tableName = $this->_sql->getTable();
        $template = "CREATE TABLE %s (%s)";

        return sprintf(
            $template,
            $tableName,
            $this->_parseColumns()
        );
    }

    /**
     * Parse column list for SQL create statement
     *
     * @return string
     */
    protected function _parseColumns()
    {
        $parts = [];
        foreach ($this->_sql->getColumns() as $column) {
            $parts[] = $this->_parseColumn($column);
        }
        return implode(', ', $parts);
    }

    /**
     * Parses a given column and returns the SQL statement for it
     *
     * @param Column\ColumnInterface $column
     * @return mixed
     */
    protected function _parseColumn(Column\ColumnInterface $column)
    {
        $method = static::$_columnTypes[get_class($column)];
        return call_user_func_array([$this, $method], array($column));
    }

    /**
     * Parses a Blob column to its SQL representation
     *
     * @param Column\Blob $column
     * @return string
     */
    protected function _getBlobColumn(Column\Blob $column)
    {
        return sprintf(
            '%s BLOB%s%s',
            $column->getName(),
            $this->_columnLength($column),
            $this->_nullableColumn($column)
        );
    }

    /**
     * Parses a DateTime column to its SQL representation
     *
     * @param Column\DateTime $column
     * @return string
     */
    protected function _getDateTimeColumn(Column\DateTime $column)
    {
        return sprintf(
            '%s TIMESTAMP%s',
            $column->getName(),
            $this->_nullableColumn($column)
        );
    }

    /**
     * Parses a Float column to its SQL representation
     *
     * @param Column\Float $column
     * @return string
     */
    protected function _getFloatColumn(Column\Float $column)
    {
        if (is_null($column->getDecimal())) {
            return sprintf(
                '%s FLOAT(%s)',
                $column->getName(),
                $column->getDigits()
            );
        }

        return sprintf(
            '%s DECIMAL(%s, %s)',
            $column->getName(),
            $column->getDigits(),
            $column->getDecimal()
        );
    }

    /**
     * Parses a boolean column to its SQL representation
     *
     * @param Column\Boolean $column
     * @return string
     */
    protected function _getBooleanColumn(Column\Boolean $column)
    {
        return sprintf(
            '%s BOOLEAN',
            $column->getName()
        );
    }

    /**
     * Parses a varchar column to its SQL representation
     *
     * @param Column\Varchar $column
     * @return string
     */
    protected function _getVarcharColumn(Column\Varchar $column)
    {
        return sprintf(
            '%s VARCHAR%s NOT NULL',
            $column->getName(),
            $this->_columnLength($column)
        );
    }

    /**
     * Parses a text column to its SQL representation
     *
     * @param Column\Text $column
     * @return string
     */
    protected function _getTextColumn(Column\Text $column)
    {
        $size = (string) $column->getSize();
        $type = 'VARCHAR('. static::$_textSizeInBytes[$size]. ')';

        return sprintf(
            '%s %s%s',
            $column->getName(),
            $type,
            $this->_nullableColumn($column)
        );
    }

    /**
     * Parses an integer column to its SQL representation
     *
     * @param Column\Integer $column
     * @return string
     */
    protected function _getIntegerColumn(Column\Integer $column)
    {

        $size = (string) $column->getSize();

        switch ($size) {
            case Column\Size::LONG:
            case Column\Size::BIG:
                $type = 'BIGINT';
                break;
            case Column\Size::SMALL:
            case Column\Size::TINY:
                $type = 'SMALLINT';
                break;
            case Column\Size::NORMAL:
            case Column\Size::MEDIUM:
            default:
                $type = 'INTEGER';
        }

        $autoIncrement = '';
        if ($column->getAutoIncrement()) {
            $autoIncrement = ' AUTO_INCREMENT';
        }

        $default = '';
        if ($column->getDefault()) {
            $default = ' DEFAULT '.$column->getDefault();
        }

        return sprintf(
            '%s %s%s%s%s%s',
            $column->getName(),
            $type,
            $this->_columnLength($column),
            $this->_nullableColumn($column),
            $autoIncrement,
            $default
        );
    }

    /**
     * Generates the NOT NULL constraint for a provided column
     *
     * @param Column\ColumnInterface $column
     * @return string
     */
    protected function _nullableColumn(Column\ColumnInterface $column)
    {
        $nullable = ' NOT NULL';
        /** @var Column\Integer|Column\Text $column */
        if ($column->getNullable()) {
            $nullable = '';
        }
        return $nullable;
    }

    /**
     * Generates the length "(x)" for a provided column
     *
     * @param Column\ColumnInterface $column
     *
     * @return string
     */
    protected function _columnLength(Column\ColumnInterface $column)
    {
        $length = '';
        /** @var Column\Integer $column */
        if ($column->getLength()) {
            $length = "({$column->getLength()})";
        }
        return $length;
    }
}
