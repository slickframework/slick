<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Delete;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * Delete SQL test case
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DeleteTest extends TestCase
{

    public function testDeleteQuery()
    {
        $delete = new Delete('tasks');
        $delete->where('id = 1')
            ->setAdapter(new CustomAdapter());
        $this->assertEquals(
            'DELETE FROM tasks WHERE id = 1',
            $delete->getQueryString()
        );
    }

    public function testAdapterExecution()
    {
        $delete = new Delete('tasks');
        $delete->where('id = 1');

        $adapter = $this->getMockBuilder(
            'Slick\Tests\Database\Fixtures\CustomAdapter'
        )->getMock();
        $adapter->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($delete),
                $this->identicalTo($delete->getParameters())
            )
            ->willReturn(1);

        $delete->setAdapter($adapter);
        $delete->execute();
    }
}
