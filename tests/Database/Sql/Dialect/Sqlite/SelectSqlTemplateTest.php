<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Dialect\Sqlite;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Dialect\Sqlite\SelectSqlTemplate;
use Slick\Database\Sql\Select;

/**
 * Class SelectSqlTemplateTest
 *
 * @package Slick\Tests\Database\Sql\Dialect\Sqlite
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectSqlTemplateTest extends TestCase
{

    public function testSelectStatement()
    {
        $select = (new Select('users'))
            ->limit(2, 10);
        $expected = "SELECT * FROM users LIMIT 2 OFFSET 10";
        $this->assertEquals(
            $expected,
            (new SelectSqlTemplate())->processSql($select)
        );
    }
}
