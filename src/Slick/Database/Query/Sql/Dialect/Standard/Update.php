<?php

/**
 * Update
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
 * Update
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Update extends Base
{
    /**
     * @readwrite
     * @var \Slick\Database\Query\SqlInterface
     */
    protected $_sql;

    /**
     * @var string The query template
     */
    protected $_insert = <<<EOT
UPDATE %s SET %s
%s
EOT;

    /**
     * Returns the SQL query string for current Select SQL Object
     *
     * @return String The SQL query string
     */
    public function getStatement()
    {
        return trim(
            sprintf(
                $this->_insert,
                $this->_sql->getTableName(),
                $this->getColumns(),
                $this->getWhere()
            )
        );
    }

    /**
     * Returns a string containing the field names seperated by commas
     *
     * @return string The field list as string
     */
    public function getColumns()
    {
        $names = array();
        foreach ($this->_sql->getFieldNames() as $field => $placeholder) {
            $names[] = "`{$field}`={$placeholder}";
        }
        return implode(', ', $names);
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
}