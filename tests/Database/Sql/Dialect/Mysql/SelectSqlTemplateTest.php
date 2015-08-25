<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Dialect\Mysql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Dialect\Mysql\SelectSqlTemplate;
use Slick\Database\Sql\Select;

/**
 * Class SelectSqlTemplateTest
 *
 * @package Slick\Tests\Database\Sql\Dialect\Mysql
 */
class SelectSqlTemplateTest extends TestCase
{

    public function testSelectStatement()
    {
        $expected = "SELECT * FROM users LIMIT 4 OFFSET 10";
        $template = new SelectSqlTemplate();
        $sql = (new Select('users'))->limit(4, 10);
        $this->assertEquals($expected, $template->processSql($sql));
    }
}
