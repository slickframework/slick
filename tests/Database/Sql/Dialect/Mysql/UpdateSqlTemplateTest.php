<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Dialect\Mysql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Dialect\Mysql\UpdateSqlTemplate;
use Slick\Database\Sql\Update;

/**
 * Class UpdateSqlTemplateTest
 *
 * @package Slick\Tests\Database\Sql\Dialect\Mysql
 */
class UpdateSqlTemplateTest extends \PHPUnit_Framework_TestCase
{

    public function testUpdateStatement()
    {
        $expected = "UPDATE users SET active = :active WHERE id = 1";
        $sql = (new Update('users'))
            ->set(['active' => true])
            ->where(['id = 1']);
        $template = new UpdateSqlTemplate();
        $this->assertEquals($expected, $template->processSql($sql));
    }
}
