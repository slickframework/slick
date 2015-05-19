<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter;

/**
 * Adapter factory test case
 *
 * @package Slick\Tests\Database
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class AdapterTest extends TestCase
{
    public function adaptersData()
    {
        return [
            'mysql' => [
                [
                    'driver' => Adapter::DRIVER_MYSQL,
                    'options' => [
                        'autoConnect' => false
                    ]
                ],
                'Slick\Database\Adapter\MysqlAdapter'
            ],
            'sqlite' => [
                [
                    'driver' => Adapter::DRIVER_SQLITE,
                    'options' => [
                        'file' => ':memory:'
                    ]
                ],
                'Slick\Database\Adapter\SqliteAdapter'
            ],
            'custom' => [
                [
                    'driver' => 'Slick\Tests\Database\Fixtures\CustomAdapter',
                    'options' => []
                ],
                'Slick\Database\Adapter\AdapterInterface'
            ]
        ];
    }

    /**
     * @dataProvider adaptersData
     * @param array $options
     * @param $type
     */
    public function testCreateAnAdapter(array $options, $type)
    {
        $adapter = Adapter::create($options, $type);
        $this->assertInstanceOf($type, $adapter);
    }

    public function testEmptyAdapterClass()
    {
        $this->setExpectedException(
            'Slick\Database\Exception\InvalidArgumentException'
        );
        Adapter::create(['driver' => null, 'options' => []]);
    }

    public function testWrongAdapterClass()
    {
        $this->setExpectedException(
            'Slick\Database\Exception\InvalidArgumentException'
        );
        Adapter::create(['driver' => '\stdClass', 'options' => []]);
    }

}
