<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\SetDataMethods;

/**
 * Set data related methods for SQL query objects test case
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SetDataMethodsTest extends TestCase
{

    use SetDataMethods;

    protected function setup()
    {
        parent::setup();
        $this->set(
            [
                'name' => 'jon',
                'email' => 'jon@example.com'
            ]
        );
    }

    protected function tearDown()
    {
        $this->dataParameters = [];
        $this->fields = [];
        parent::tearDown();
    }

    public function testParameters()
    {
        $this->assertEquals(
            [
                ':name' => 'jon',
                ':email' => 'jon@example.com'
            ],
            $this->getParameters()
        );
    }

    public function testFieldList()
    {
        $expected = 'name, email';
        $this->assertEquals($expected, $this->getFieldList());
    }

    public function testPlaceHoldersList()
    {
        $expected = ':name, :email';
        $this->assertEquals($expected, $this->getPlaceholderList());
    }

    public function testFieldsArray()
    {
        $expected = ['name', 'email'];
        $this->assertEquals($expected, $this->getFields());
    }
}
