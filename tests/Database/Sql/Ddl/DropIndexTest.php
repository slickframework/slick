<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\DropIndex;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * DDL Drop Index query test case
 *
 * @package Slick\Tests\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DropIndexTest extends TestCase
{

    public function testDropIndexQuery()
    {
        $delete = new DropIndex('test','tasks');
        $delete->setAdapter(new CustomAdapter());
        $this->assertEquals(
            'DROP INDEX test ON tasks',
            $delete->getQueryString()
        );
    }
}
