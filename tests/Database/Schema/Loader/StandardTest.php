<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Schema\Loader;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\RecordList;
use Slick\Database\Schema\Loader\Standard;
use Slick\Database\Schema\Table;

/**
 * Class Standard loader Test case.
 *
 * @package Slick\Tests\Database\Schema\Loader
 */
class StandardTest extends TestCase
{

    protected $resultSets = [
        0 => [
            ['TABLE_NAME' => 'users'],
        ],
        1 => [
            [
                'columnName' => 'id',
                'type' => 'int',
                'length' => null,
                'precision' => 10,
                'default' => null,
                'isNullable' => 'NO'
            ],
            [
                'columnName' => 'active',
                'type' => 'boolean',
                'length' => null,
                'precision' => 1,
                'default' => null,
                'isNullable' => 'NO'
            ],
            [
                'columnName' => 'name',
                'type' => 'tinytext',
                'length' => 255,
                'precision' => null,
                'default' => null,
                'isNullable' => 'NO'
            ],
            [
                'columnName' => 'test',
                'type' => 'varchar',
                'length' => 255,
                'precision' => null,
                'default' => null,
                'isNullable' => 'NO'
            ],
        ],
        2 => [
            [
                'constraintName' => 'PRIMARY',
                'constraintType' => 'PRIMARY',
                'columnName' => 'id',
                'referenceTable' => null,
                'referenceColumn' => null,
                'onUpdate' => null,
                'onDelete' => null
            ],
            [
                'constraintName' => 'usersUniqueName',
                'constraintType' => 'UNIQUE',
                'columnName' => 'name',
                'referenceTable' => null,
                'referenceColumn' => null,
                'onUpdate' => null,
                'onDelete' => null
            ],
            [
                'constraintName' => 'userFk',
                'constraintType' => 'FOREIGN KEY',
                'columnName' => 'user_id',
                'referenceTable' => 'users',
                'referenceColumn' => 'id',
                'onUpdate' => null,
                'onDelete' => 'CASCADE'
            ],
            [
                'constraintName' => 'userFk',
                'constraintType' => 'FOO',
                'columnName' => 'user_id',
                'referenceTable' => 'users',
                'referenceColumn' => 'id',
                'onUpdate' => null,
                'onDelete' => 'CASCADE'
            ],
        ]
    ];

    public function testGetSchema()
    {
        $schema = (new Standard(['adapter' => $this->getAdapter()]))
            ->getSchema();
        /** @var Table $table */
        $table = $schema->getTables()['users'];
        $column = $table->getColumns()['name'];
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Text', $column);

    }

    public function testAdapterRetrieve()
    {

        $adapter = $this->getAdapter();
        $schema = new Standard(['adapter' => $adapter]);
        $this->assertSame($adapter, $schema->getAdapter());
    }

    /**
     * @return MockObject|AdapterInterface
     */
    private function getAdapter()
    {
        /** @var AdapterInterface|MockObject $adapter */
        $adapter = $this->getMockBuilder('Slick\Database\Adapter\AdapterInterface')
            ->getMock();
        $adapter->method('query')
            ->willReturnOnConsecutiveCalls(
                new RecordList(['data' => $this->resultSets[0]]),
                new RecordList(['data' => $this->resultSets[1]]),
                new RecordList(['data' => $this->resultSets[2]])
            );
        return $adapter;
    }
}
