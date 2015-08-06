<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;

use Slick\Database\Sql\Ddl\Column\Decimal as FloatColumn;

/**
 * Float column test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class FloatTest extends TestCase
{

    /**
     * @var FloatColumn
     */
    protected $float;

    protected function setUp()
    {
        parent::setUp();
        $this->float = new FloatColumn('score', 2);
    }

    protected function tearDown()
    {
        $this->float = null;
        parent::tearDown();
    }

    public function testGetDecimal()
    {
        $this->assertNull($this->float->getDecimal());
    }

    public function testGetDigits()
    {
        $this->assertEquals(2, $this->float->getDigits());
    }
}
