<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\CreateIndex;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * Class CreateIndexTest
 *
 * @package Slick\Tests\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateIndexTest extends TestCase
{

    public function testCreateIndex()
    {
        $create = new CreateIndex('test', 'tasks');
        $create->setColumns('created, updated')
            ->setAdapter(new CustomAdapter());

        $this->assertEquals(
            'CREATE INDEX test ON tasks (created, updated)',
            $create->getQueryString()
        );
    }
}
