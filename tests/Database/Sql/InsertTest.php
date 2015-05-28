<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Insert;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * Insert SQL query object test case
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class InsertTest extends TestCase
{

    public function testInsertQuery()
    {
        $insert = new Insert('tasks');
        $insert->set(
            [
                'name' => 'jon',
                'email' => 'jon@example.com'
            ]
        )->setAdapter(new CustomAdapter());
        $expected = "INSERT INTO tasks (name, email) VALUES (:name, :email)";
        $params =[
            ':name' => 'jon',
            ':email' => 'jon@example.com'
        ];
        $this->assertEquals($expected, $insert->getQueryString());
        $this->assertEquals($params, $insert->getParameters());
    }
}
