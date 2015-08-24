<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Dialect\Sqlite;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Dialect\Sqlite\UpdateSqlTemplate;
use Slick\Database\Sql\Update;

/**
 * Class UpdateSqlTemplateTest
 *
 * @package Slick\Tests\Database\Sql\Dialect\Sqlite
 */
class UpdateSqlTemplateTest extends TestCase
{

    public function testUpdateTableTemplate()
    {
        $update = (new Update('users'))
            ->set(['test' => '12345'])
            ->where(['id = 12']);
        $expected = "UPDATE users SET test = :test WHERE id = 12";
        $this->assertEquals(
            $expected,
            (new UpdateSqlTemplate())->processSql($update)
        );
    }
}
