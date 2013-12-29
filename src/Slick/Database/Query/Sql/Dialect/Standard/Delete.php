<?php

/**
 * Delete
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
 * Delete
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Delete extends Base
{
     /**
     * @readwrite
     * @var \Slick\Database\Query\SqlInterface
     */
    protected $_sql;

    /**
     * @var string The query template
     */
    protected $_delete = <<<EOT
DELETE FROM %s
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
                $this->_delete,
                $this->_sql->getTableName(),
                $this->getWhere()
            )
        );
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