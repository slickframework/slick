<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Update;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * Class UpdateTest
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UpdateTest extends TestCase
{

    public function testUpdateQuery()
    {
        $insert = new Update('tasks');
        $insert
            ->set(
                [
                    'name' => 'jon',
                    'email' => 'jon@example.com'
                ]
            )
            ->where(['id = :id' => [':id' => 1]])
            ->setAdapter(new CustomAdapter());
        $expected =
            "UPDATE tasks SET (name = :name, email = :email) WHERE id = :id";
        $params =[
            ':name' => 'jon',
            ':email' => 'jon@example.com',
            ':id' => 1
        ];
        $this->assertEquals($expected, $insert->getQueryString());
        $this->assertEquals($params, $insert->getParameters());
    }
}
