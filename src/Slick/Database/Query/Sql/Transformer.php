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
    Slick\Database\Exception,
    Slick\Database\Query\Sql\SqlInterface,
    Slick\Database\Query\Sql\Dialect,
    Slick\Database\Query\Sql\Dialect\Dialect as SqlDialect;

/**
 * Transformer
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property SqlInterface $sql
 */
class Transformer extends Base
{

    /**
     * @readwrite
     * @var SqlDialect
     */
    protected $_dialect = null;

    /**
     * @readwrite
     * @var SqlInterface
     */
    protected $_sql = null;

    /**
     * Factory method to create a sql transformer object
     * 
     * @param string $dialect The SQL dialect name
     * 
     * @return Transformer
     */
    public static function create($dialect)
    {
        return new static(['dialect' => $dialect]);
    }

    /**
     * Transforms a SQL statement object int its correct string form
     * 
     * @param SqlInterface $sql
     * 
     * @return string The sql query string for current dialect
     */
    public function transform(SqlInterface $sql)
    {
        return $this->setSql($sql)
            ->sql->getStatement();
    }

    /**
     * Sets the internal SQL object for current dialect
     *
     * @param SqlInterface $sql
     *
     * @throws \Slick\Database\Exception\UndefinedSqlDialectException
     * @return Transformer
     */
    public function setSql(SqlInterface $sql)
    {

        if (class_exists($this->_dialect)) {
            $class = $this->_dialect;
            $dialect = new $class(['sql' => $sql]);
            if (!($dialect instanceof Dialect\Dialect)) {
                throw new Exception\UndefinedSqlDialectException(
                    "The dialect '{$this->_dialect}' is not defined."
                );
            }
            $this->_sql = $dialect;
            return $this;
        }
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