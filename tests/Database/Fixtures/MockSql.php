<?php
 /**
 * MockSql
 *
 * @package Slick\Tests\Database\Fixtures
 * @author    Filipe Silva <filipe.silva@sata.pt>
 * @copyright 2014-2015 Grupo SATA
 * @since     v0.0.0
 */

namespace Slick\Tests\Database\Fixtures;


use Slick\Database\Sql\AbstractSql;

class MockSql extends AbstractSql
{

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        return null;
    }
}