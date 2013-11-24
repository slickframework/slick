<?php

/**
 * Standard
 *
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect;

use Slick\Common\Base;

/**
 * Standard
 *
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Standard extends Base implements Dialect
{

    /**
     * @readwrite
     * @var \Slick\Database\Query\SqlInterface
     */
    protected $_sql;

    /**
     * Retrieves the SQL statment for current dialect
     * 
     * @return string The correct SQL statment
     */
    public function getStatement()
    {
        $parts = explode('\\', get_class($this->_sql));
        $name = array_pop($parts);

        $statement = null;

        switch ($name) {
            case 'Select':
                $statement = $this->select();
                break;

            case 'Insert':
                $statement = $this->insert();
                break;

            case 'Update':
                $statement = $this->update();
                break;

            case 'Delete':
                $statement = $this->delete();
                break;
        }
        return $statement;
    }

    /**
     * Parses a Select SQL object into its string query
     * 
     * @return string The SQL Select query statement string
     */
    public function select()
    {
        $dialect = new Standard\Select(array('sql' => $this->_sql));
        return $dialect->getStatement();
    }

    /**
     * Parses an Insert SQL object into its string query
     * 
     * @return string The SQL Insert query statement string
     */
    public function insert()
    {
        $dialect = new Standard\Insert(array('sql' => $this->_sql));
        return $dialect->getStatement();
    }

    /**
     * Parses an Update SQL object into its string query
     * 
     * @return string The SQL Update query statement string
     */
    public function update()
    {
        $dialect = new Standard\Update(array('sql' => $this->_sql));
        return $dialect->getStatement();
    }

    /**
     * Parses an Delete SQL object into its string query
     * 
     * @return string The SQL Delete query statement string
     */
    public function delete()
    {
        $dialect = new Standard\Delete(array('sql' => $this->_sql));
        return $dialect->getStatement();
    }
}