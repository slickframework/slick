<?php

/**
 * Select
 * 
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\Standard;

use Slick\Common\Base;

/**
 * Select
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends Base
{
    /**
     * @readwrite
     * @var \Slick\Database\Query\SqlInterface
     */
    protected $_sql;

    /**
     * @var string The query template
     */
    protected $_select = <<<EOT
SELECT <fields><joinFields> FROM <tableName>
<joins>
<where>
<groupBy>
<orderBy>
<limit>
EOT;

    /**
     * Returns the SQL query string for current Select SQL Object
     * 
     * @return String The SQL query string
     */
    public function getStatement()
    {
        return $this->_fixBlanks(
            trim(
                str_replace(
                    array(
                        '<fields>', '<joinFields>', '<tableName>',
                        '<joins>', '<where>', '<groupBy>',
                        '<orderBy>', '<limit>'
                    ),
                    array(
                        $this->getFields(),
                        $this->getJoinFields(),
                        $this->_sql->getTableName(),
                        $this->getJoins(), 
                        $this->getWhere(),
                        $this->getGroupBy(),
                        $this->getOrderBy(),
                        $this->getLimit()
                    ),
                    $this->_select
                )
            )
        );
    }

    /**
     * Returns the field list from Select object
     * 
     * @return string The field list
     */
    public function getFields()
    {
        $joins = $this->_sql->getJoins();
        $fields = $this->_sql->getFields();

        if (!is_array($fields)) {
            return $fields;
        }

        if (count($joins) > 0 && $this->_sql->prefixTableName) {
            $table = $this->_sql->getTableName();
            foreach ($fields as $key => $field) {
                $fields[$key] = "{$table}.{$field}";
            }
        }

        return implode(', ', $fields);
    }

    /**
     * Returns the join fields for this query
     * 
     * @return string The comma seperated field names
     */
    public function getJoinFields()
    {
        $fields = null;
        $tmpFields = array();
        $joins = $this->_sql->getJoins();

        if (count($joins) > 0) {
            foreach ($joins as $join) {

                $tmpJoin = array();
                foreach ($join['fields'] as $field) {
                    $tmpJoin[] = "{$join['table']}.{$field}";
                }
                $str = implode(', ', $tmpJoin);
                if (strlen($str) > 1) {
                    $tmpFields[] = implode(', ', $tmpJoin);
                }
            }
            $fields = ', '. implode(', ', $tmpFields);
            if (trim($fields) == ',') $fields = null;
        }

        return $fields;
    }

    /**
     * Returns the JOIN clauses for this query
     * 
     * @return string The join clauses string
     */
    public function getJoins()
    {
        $template = "%s JOIN %s ON %s";
        $joinsStr = null;

        $joins = $this->_sql->getJoins();
        $tmpJoin = array();
        if (count($joins) > 0) {
            foreach ($joins as $join) {
                $tmpJoin[] = sprintf(
                    $template,
                    $join['type'],
                    $join['table'],
                    $join['onClause']
                );
            }
            $joinsStr = implode(PHP_EOL, $tmpJoin);
        }

        return $joinsStr;
    }

    /**
     * Returns the WHERE clause for this query
     * 
     * @return string The where clause string
     */
    public function getWhere()
    {
        $template = "WHERE %s";
        $where = null;
        if (count($this->_sql->conditions->predicates) > 0) {
            $where = trim(
                sprintf($template, $this->_sql->conditions->toString())
            );
        }

        return $where;
    }

    /**
     * Returns the order by clause for this query
     * 
     * @return string The order by clause string
     */
    public function getOrderBy()
    {
        $template = "ORDER BY %s";
        $orderBy = null;
        if (!is_null($this->_sql->orderBy)) {
            $orderBy = trim(sprintf($template, $this->_sql->getOrderBy()));
        }
        return $orderBy;
    }

    /**
     * Returns the group by clause for this query
     * 
     * @return string The group by clause string
     */
    public function getGroupBy()
    {
        $template = "GROUP BY %s";
        $groupBy = null;
        if (!is_null($this->_sql->groupBy)) {
            $groupBy = trim(sprintf($template, $this->_sql->getGroupBy()));
        }
        return $groupBy;
    }

    /**
     * Returns the limit clause for this query
     * 
     * @return string The limit clause string
     */
    public function getLimit()
    {
        if ($this->_sql->limit <= 0) {
            return null;
        }

        if ($this->_sql->offset > 0) {
            return "LIMIT {$this->_sql->offset}, {$this->_sql->limit}";
        } 
        return "LIMIT {$this->_sql->limit}";
    }

    /**
     * Removes the blank lines in the query string
     * 
     * @param string $str The query string to fix
     * 
     * @return string The fixed query string.
     */
    protected function _fixBlanks($str)
    {
        $lines = explode(PHP_EOL, $str);
        $clean = array();
        foreach ($lines as $line) {
            if (trim($line) != "") {
                $clean[] = $line;
            }
        }

        return implode(PHP_EOL, $clean);
    }

}