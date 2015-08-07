<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Common\Utils\ArrayMethods;
use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Constraint;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * Class CreateTableSqlTemplate
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
{

    /**
     * @var SqlInterface|CreateTable
     */
    protected $sql;

    /**
     * @var array
     */
    protected static $columnMethods = [
        'Slick\Database\Sql\Ddl\Column\Integer' => 'getIntegerColumn',
        'Slick\Database\Sql\Ddl\Column\Text' => 'getTextColumn',
        'Slick\Database\Sql\Ddl\Column\Varchar' => 'getVarcharColumn',
        'Slick\Database\Sql\Ddl\Column\Boolean' => 'getBooleanColumn',
        'Slick\Database\Sql\Ddl\Column\Decimal' => 'getFloatColumn',
        'Slick\Database\Sql\Ddl\Column\DateTime' => 'getDateTimeColumn',
        'Slick\Database\Sql\Ddl\Column\Blob' => 'getBlobColumn'
    ];
    protected static $constraintMethods = [
        'Slick\Database\Sql\Ddl\Constraint\Primary' => 'getPrimaryConstraint',
        'Slick\Database\Sql\Ddl\Constraint\Unique' => 'getUniqueConstraint',
        'Slick\Database\Sql\Ddl\Constraint\ForeignKey' => 'getFKConstraint',
    ];
    /**
     * @var array
     */
    protected static $textSizeInBytes = [
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
        $this->sql = $sql;
        $tableName = $this->sql->getTable();
        $template = "CREATE"." TABLE %s (%s)";
        $parts = ArrayMethods::clean(
            [$this->parseColumns(), $this->parseConstraints()]
        );
        return sprintf(
            $template,
            $tableName,
            implode(', ', $parts)
        );
    }

    /**
     * Parse column list for SQL create statement
     *
     * @return string
     */
    protected function parseColumns()
    {
        $parts = [];
        foreach ($this->sql->getColumns() as $column) {
            $parts[] = $this->parseColumn($column);
        }
        return implode(', ', $parts);
    }

    /**
     * Parses a given column and returns the SQL statement for it
     *
     * @param Column\ColumnInterface $column
     * @return string
     */
    protected function parseColumn(Column\ColumnInterface $column)
    {
        $method = static::$columnMethods[get_class($column)];
        return call_user_func_array([$this, $method], array($column));
    }

    /**
     * Parse constraint list for SQL create statement
     *
     * @return string
     */
    protected function parseConstraints()
    {
        $parts = [];
        foreach ($this->sql->getConstraints() as $constraint) {
            $cons = $this->parseConstraint($constraint);
            if ($cons) {
                $parts[] = $cons;
            }
        }
        return implode(', ', $parts);
    }

    /**
     * Parses a given constraint and returns the SQL statement for it
     *
     * @param Constraint\ConstraintInterface $cons
     * @return string
     */
    protected function parseConstraint(Constraint\ConstraintInterface $cons)
    {
        $method = static::$constraintMethods[get_class($cons)];
        return call_user_func_array([$this, $method], array($cons));
    }

    /**
     * Parse a Foreign Key constraint to its SQL representation
     *
     * @param Constraint\ForeignKey $cons
     * @return string
     */
    protected function getFKConstraint(Constraint\ForeignKey $cons)
    {
        $onDelete = '';
        if ($cons->getOnDelete()) {
            $onDelete = " ON DELETE {$cons->getOnDelete()}";
        }
        $onUpdated = '';
        if ($cons->getOnUpdate()) {
            $onUpdated = " ON UPDATE {$cons->getOnUpdate()}";
        }
        return sprintf(
            'CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s(%s)%s%s',
            $cons->getName(),
            $cons->getColumn(),
            $cons->getReferenceTable(),
            $cons->getReferenceColumn(),
            $onDelete,
            $onUpdated
        );
    }

    /**
     * Parse a Primary Key constraint to its SQL representation
     *
     * @param Constraint\Primary $constraint
     * @return string
     */
    protected function getPrimaryConstraint(Constraint\Primary $constraint)
    {
        $columns = implode(', ', $constraint->getColumnNames());
        return sprintf(
            'CONSTRAINT %s PRIMARY KEY (%s)',
            $constraint->getName(),
            $columns
        );
    }

    /**
     * Parse a Unique constraint to its SQL representation
     *
     * @param Constraint\Unique $constraint
     * @return string
     */
    protected function getUniqueConstraint(Constraint\Unique $constraint)
    {
        return sprintf(
            'CONSTRAINT %s UNIQUE (%s)',
            $constraint->getName(),
            $constraint->getColumn()
        );
    }

    /**
     * Parses a Blob column to its SQL representation
     *
     * @param Column\Blob $column
     * @return string
     */
    protected function getBlobColumn(Column\Blob $column)
    {
        return sprintf(
            '%s BLOB%s%s',
            $column->getName(),
            $this->columnLength($column),
            $this->nullableColumn($column)
        );
    }

    /**
     * Parses a DateTime column to its SQL representation
     *
     * @param Column\DateTime $column
     * @return string
     */
    protected function getDateTimeColumn(Column\DateTime $column)
    {
        return sprintf(
            '%s TIMESTAMP%s',
            $column->getName(),
            $this->nullableColumn($column)
        );
    }

    /**
     * Parses a Float column to its SQL representation
     *
     * @param Column\Decimal $column
     * @return string
     */
    protected function getFloatColumn(Column\Decimal $column)
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
    protected function getBooleanColumn(Column\Boolean $column)
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
    protected function getVarcharColumn(Column\Varchar $column)
    {
        return sprintf(
            '%s VARCHAR%s NOT NULL',
            $column->getName(),
            $this->columnLength($column)
        );
    }

    /**
     * Parses a text column to its SQL representation
     *
     * @param Column\Text $column
     * @return string
     */
    protected function getTextColumn(Column\Text $column)
    {
        $size = (string) $column->getSize();
        $type = 'VARCHAR('. static::$textSizeInBytes[$size]. ')';
        return sprintf(
            '%s %s%s',
            $column->getName(),
            $type,
            $this->nullableColumn($column)
        );
    }

    /**
     * Parses an integer column to its SQL representation
     *
     * @param Column\Integer $column
     * @return string
     */
    protected function getIntegerColumn(Column\Integer $column)
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
            $this->columnLength($column),
            $this->nullableColumn($column),
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
    protected function nullableColumn(Column\ColumnInterface $column)
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
    protected function columnLength(Column\ColumnInterface $column)
    {
        $length = '';
        /** @var Column\Integer $column */
        if ($column->getLength()) {
            $length = "({$column->getLength()})";
        }
        return $length;
    }
}
