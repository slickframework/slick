<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database;

use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Delete;
use Slick\Database\Sql\Insert;
use Slick\Database\Sql\Select;
use Slick\Database\Sql\Update;

/**
 * SQL query object factory
 *
 * @package Slick\Database
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
final class Sql
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Creates a factory with the provided adapter
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Creates an SQL factory object
     *
     * @param AdapterInterface $adapter
     *
     * @return Sql
     */
    public static function createSql(AdapterInterface $adapter)
    {
        $sql = new Sql($adapter);
        return $sql;
    }

    /**
     * Creates a Select statement object
     *
     * @param string $tableName
     * @param string|string[] $fields
     *
     * @return Select
     */
    public function select($tableName, $fields = ['*'])
    {
        $sql = new Select($tableName, $fields);
        $sql->setAdapter($this->adapter);
        return $sql;
    }

    /**
     * Creates a Delete statement object
     *
     * @param string $tableName
     *
     * @return Delete
     */
    public function delete($tableName)
    {
        $sql = new Delete($tableName);
        $sql->setAdapter($this->adapter);
        return $sql;
    }

    /**
     * Creates an Insert statement object
     *
     * @param string $tableName
     *
     * @return Insert
     */
    public function insert($tableName)
    {
        $sql = new Insert($tableName);
        $sql->setAdapter($this->adapter);
        return $sql;
    }
    /**
     * Creates an Update statement object
     *
     * @param string $tableName
     *
     * @return Update
     */
    public function update($tableName)
    {
        $sql = new Update($tableName);
        $sql->setAdapter($this->adapter);
        return $sql;
    }
}