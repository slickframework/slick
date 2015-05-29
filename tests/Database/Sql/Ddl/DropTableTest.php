<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\DropTable;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * Standard Drop Table SQL template
 *
 * @package Slick\Tests\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DropTableTest extends TestCase
{

    public function testDropTableQuery()
    {
        $delete = new DropTable('tasks');
        $delete->setAdapter(new CustomAdapter());
        $this->assertEquals(
            'DROP TABLE tasks',
            $delete->getQueryString()
        );
    }
}
