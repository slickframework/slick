<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Dialect;
use Slick\Tests\Database\Fixtures\CustomQuery;

/**
 * Dialect factory test case
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class DialectTest extends TestCase
{

    public function testCreateStandardDialect()
    {
        $dialect = Dialect::create(Dialect::STANDARD, new CustomQuery());
        $this->assertInstanceOf("Slick\\Database\\Sql\\Dialect\\Standard", $dialect);
    }

    public function testCreatingUnknownDialect()
    {
        $this->setExpectedException(
            "Slick\\Database\\Exception\\InvalidArgumentException"
        );
        Dialect::create('unknown', new CustomQuery());
    }

    public function testCreatingInvalidDialect()
    {
        $this->setExpectedException(
            "Slick\\Database\\Exception\\InvalidArgumentException"
        );
        Dialect::create('stdClass', new CustomQuery());
    }
}
