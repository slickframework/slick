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
}