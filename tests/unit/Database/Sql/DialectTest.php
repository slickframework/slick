<?php

/**
 * SQL dialect test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql;

use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\Dialect\DialectInterface;
use Slick\Database\Sql\Select;
use Slick\Database\Sql\SqlInterface;

/**
 * SQL dialect test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DialectTest extends \Codeception\TestCase\Test
{

    /**
     * Try to create an unknown dialect
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function createUnknownDialect()
    {
        Dialect::create('_unknown_', new CustomSql());
    }

    /**
     * Trying to create custom dialect
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function createCustomDialect()
    {
        $dialect = Dialect::create('\Database\Sql\CustomDialect', new CustomSql());
        $this->assertInstanceOf('\Database\Sql\CustomDialect', $dialect);

        Dialect::create('\StdClass', new CustomSql());
    }
}

/**
 * Class CustomSql Mock class
 * @package Database\Sql
 */
class CustomSql implements SqlInterface
{

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        // TODO: Implement getQueryString() method.
    }

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return SqlInterface
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        // TODO: Implement setAdapter() method.
    }
}

class CustomDialect implements Dialect\DialectInterface
{

    /**
     * Sets the SQL object to be processed
     *
     * @param SqlInterface $sql
     * @return DialectInterface
     */
    public function setSql(SqlInterface $sql)
    {
        // TODO: Implement setSql() method.
    }

    /**
     * Returns the SQL statement for current SQL object
     *
     * @return string
     */
    public function getSqlStatement()
    {
        // TODO: Implement getSqlStatement() method.
    }
}