<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Dialect\FieldListAwareInterface;
use Slick\Database\Sql\Select;
use Slick\Database\Sql\SqlInterface;

/**
 * Standard Select SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectSqlTemplate extends AbstractSqlTemplate
{

    /**
     * @var Select
     */
    protected $sql;

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->sql = $sql;
        $this->getSelectFieldsAndTable()
            ->setJoins()
            ->getWhereConditions()
            ->setOrder()
            ->setLimit();
        return $this->statement;
    }
    /**
     * Sets the fields for this select query
     *
     * @return SelectSqlTemplate
     */
    protected function getSelectFieldsAndTable()
    {
        $template = "SELECT %s FROM %s";
        if ($this->sql->isDistinct()) {
            $template = "SELECT DISTINCT %s FROM %s";
        }
        $this->statement = sprintf(
            $template,
            $this->getFieldList(),
            $this->sql->getTable()
        );
        return $this;
    }
    /**
     * Sets table field list
     * @return string
     */
    protected function getFieldList()
    {
        $fields = [];
        $fields[] = $this->getFieldsFor($this->sql);
        foreach ($this->sql->getJoins() as $join) {
            $str = $this->getFieldsFor($join);
            if (!$str) {
                continue;
            }
            $fields[] = $str;
        }
        return implode(', ', $fields);
    }
    /**
     * Retrieve a field list from a FieldListAwareInterface object
     *
     * @param FieldListAwareInterface $object
     * @return bool|string
     */
    protected function getFieldsFor(FieldListAwareInterface $object)
    {
        $fields = $object->getFields();
        $fieldsStr = $fields;

        if (is_array($fields)) {
            $fieldsStr = $this->getFieldsWithAlias(
                $fields,
                $object->getAlias()
            );
        }
        return (is_null($fields)) ? false : $fieldsStr;
    }

    /**
     * Returns the field names with provided alias ready to be used in query
     *
     * @param array $fields
     * @param string $alias
     *
     * @return string
     */
    protected function getFieldsWithAlias($fields, $alias)
    {
        $fieldList = [];
        foreach ($fields as $field) {
            $fieldList[] = "{$alias}.{$field}";
        }
        return implode(', ', $fieldList);
    }

    /**
     * Sets the joins for this select statement
     *
     * @return SelectSqlTemplate
     */
    protected function setJoins()
    {
        $joins = $this->sql->getJoins();
        foreach ($joins as $join) {
            $this->statement .= $this->createJoinStatement($join);
        }
        return $this;
    }
    /**
     * Sets the order by clause
     *
     * @return SelectSqlTemplate
     */
    protected function setOrder()
    {
        $order = $this->sql->getOrder();
        if (!(is_null($order) || empty($order))) {
            $this->statement .= " ORDER BY {$order}";
        }
        return $this;
    }
    /**
     * Set limit clause
     *
     * @return SelectSqlTemplate
     */
    protected function setLimit()
    {
        if (
            is_null($this->sql->getLimit()) ||
            intval($this->sql->getLimit()) < 1
        ) {
            return $this;
        }
        if ($this->sql->getOffset() > 0) {
            return $this->setLimitWithOffset();
        }
        return  $this->setSimpleLimit();
    }
    /**
     * Set limit clause when using offset
     *
     * @return SelectSqlTemplate
     */
    protected function setLimitWithOffset()
    {
        $this->statement .= " OFFSET {$this->sql->getOffset()} ROWS";
        $this->setSimpleLimit();
        return $this;
    }
    /**
     * Set limit clause for simple limits
     *
     * @return SelectSqlTemplate
     */
    protected function setSimpleLimit()
    {
        $this->statement .= " FETCH FIRST {$this->sql->getLimit()} ROWS ONLY";
        return $this;
    }
    /**
     * Sets the proper join syntax for a provided join object
     *
     * @param Select\Join $join
     *
     * @return string
     */
    protected function createJoinStatement(Select\Join $join)
    {
        $template = " %s JOIN %s%s ON %s";
        $alias = (is_null($join->getAlias())) ?
            null : " AS {$join->getAlias()}";
        return sprintf(
            $template,
            $join->getType(),
            $join->getTable(),
            $alias,
            $join->getOnClause()
        );
    }
}