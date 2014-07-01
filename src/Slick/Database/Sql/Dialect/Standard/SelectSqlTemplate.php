<?php

/**
 * Standard Select SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Dialect\FieldListAwareInterface;
use Slick\Database\Sql\Select;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Select SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectSqlTemplate implements SqlTemplateInterface
{
    /**
     * @var Select
     */
    protected $_sql;

    /**
     * @var string
     */
    private $_statement = '';

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->_sql = $sql;
        $this->_getSelectFieldsAndTable()
            ->_getWhereConditions()
            ->_setJoins()
            ->_setOrder()
            ->_setLimit();
        return $this->_statement;
    }

    /**
     * Sets the fields for this select query
     *
     * @return SelectSqlTemplate
     */
    protected function _getSelectFieldsAndTable()
    {
        $template = "SELECT %s FROM %s";
        if ($this->_sql->isDistinct()) {
            $template = "SELECT DISTINCT %s FROM %s";
        }
        $this->_statement = sprintf(
            $template,
            $this->_getFieldList(),
            $this->_sql->getTable()
        );
        return $this;
    }

    /**
     * Sets table field list
     * @return string
     */
    protected function _getFieldList()
    {
        $fields[] = $this->_getFieldsFor($this->_sql);

        foreach ($this->_sql->getJoins() as $join) {
            $str = $this->_getFieldsFor($join);
            if (!$str) {
                continue;
            }
            $fields[] = $str;
        }
        return implode(', ', $fields);
    }

    protected function _getFieldsFor(FieldListAwareInterface $object)
    {
        if (is_null($object->getFields())) {
            return false;
        }
        if (is_string($object->getFields())) {
            return $object->getFields();
        }
        $alias = (is_null($object->getAlias())) ?
            $object->getTable() : $object->getAlias();
        $fields = [];
        foreach($object->getFields() as $field) {
            $fields[] = "{$alias}.{$field}";
        }

        return implode(', ', $fields);
    }

    /**
     * Sets the where clause for this select statement
     *
     * @return SelectSqlTemplate
     */
    protected function _getWhereConditions()
    {
        $where = $this->_sql->getWhereStatement();
        if ($where) {
            $this->_statement .=  " WHERE {$where}";
        }
        return $this;

    }

    /**
     * Sets the joins for this select statement
     *
     * @return SelectSqlTemplate
     */
    protected function _setJoins()
    {
        $joins = $this->_sql->getJoins();
        foreach ($joins as $join) {
            $this->_statement .= $this->_createJoinStatement($join);
        }
        return $this;
    }

    /**
     * Sets the order by clause
     *
     * @return SelectSqlTemplate
     */
    protected function _setOrder()
    {
        $order = $this->_sql->getOrder();
        if (!(is_null($order) || empty($order))) {
            $this->_statement .= " ORDER BY {$order}";
        }
        return $this;
    }

    protected function _setLimit()
    {
        if ($this->_sql->getOffset() > 0) {
            return $this->_setLimitWithOffset();
        }
        return  $this->_setSimpleLimit();
    }

    protected function _setLimitWithOffset()
    {
        $this->_statement .= "OFFSET {$this->_sql->getOffset()} ROWS";
        $this->_setSimpleLimit();
        return $this;
    }

    protected function _setSimpleLimit()
    {
        $this->_statement .= " FETCH FIRST {$this->_sql->getLimit()} ROWS ONLY";
        return $this;
    }

    /**
     * Sets the proper join syntax for a provided join object
     *
     * @param Select\Join $join
     *
     * @return string
     */
    protected function _createJoinStatement(Select\Join $join )
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