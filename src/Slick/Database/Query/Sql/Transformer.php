<?php

/**
 * Transformer
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

use Slick\Common\Base,
    Slick\Database\Exception;

/**
 * Transformer
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Transformer extends Base
{

    /**
     * @readwrite
     * @var \Slick\Database\Query\Sql\Dialect
     */
    protected $_dialect = null;

    /**
     * @readwrite
     * @var \Slick\Database\Query\Sql\SqlInterface
     */
    protected $_sql = null;

    /**
     * Factory method to create a sql transformer object
     * 
     * @param string $dialect The SQL dialect name
     * 
     * @return \Slick\Database\Query\Sql\Transformer
     */
    public static function create($dialect)
    {
        $transformer = new Static();
        $transformer->setDialect($dialect);
        return $transformer;
    }

    /**
     * Transforms a SQL statement object int its correct string form
     * 
     * @param \Slick\Database\Query\Sql\SqlInterface $sql
     * 
     * @return string The sql query string for current dialect
     */
    public function transform(\Slick\Database\Query\Sql\SqlInterface $sql)
    {
        $this->setSql($sql);
        return $this->getSql()->getStatement();
    }

    /**
     * Sets the internal SQL object for current dialect
     * 
     * @param \Slick\Database\Query\Sql\SqlInterface $sql
     */
    public function setSql(\Slick\Database\Query\Sql\SqlInterface $sql)
    {

        switch ($this->_dialect) {
            case 'Mysql':
                $this->_sql = new Dialect\Mysql(array('sql' => $sql));
                break;

            case 'SQLite':
                $this->_sql = new Dialect\SQLite(array('sql' => $sql));
                break;
            
            default:
                throw new Exception\UndefinedSqlDialectException(
                    "The dialect '{$this->_dialect}' is not defined."
                );
                
        }

        return $this;
    }
}