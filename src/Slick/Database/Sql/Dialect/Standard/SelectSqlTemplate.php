<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 6/25/14
 * Time: 11:54 PM
 */

namespace Slick\Database\Sql\Dialect\Standard;


use Slick\Database\Sql\Select;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

class SelectSqlTemplate implements SqlTemplateInterface
{
    /**
     * @var Select
     */
    protected $_sql;

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
        $str = '';
        $str .= $this->_getSelectFieldsAndTable();
        return $str;
    }

    protected function _getSelectFieldsAndTable()
    {
        $template = "SELECT %s FROM %s";
        return sprintf(
            $template,
            $this->_getFieldList(),
            $this->_sql->getTable()
        );
    }

    protected function _getFieldList()
    {
        if (is_string($this->_sql->getFields())) {
            return $this->_sql->getFields();
        }

        return implode(', ', $this->_sql->getFields());
    }
}