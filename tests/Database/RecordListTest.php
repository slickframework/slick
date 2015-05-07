<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\RecordList;

/**
 * RecordList test case
 *
 * @package Slick\Tests\Database
 * @author  Filipe Silva <silvam.filipe@sata.pt>
 */
class RecordListTest extends TestCase
{

    /**
     * @var RecordList
     */
    protected $recordList;

    protected $data = ['one', 'two', 'three'];

    /**
     * Creates record list
     */
    public function setup()
    {
        parent::setup();
        $this->recordList = new RecordList(['data' => $this->data]);
    }

    /**
     * Clears record list after each test
     */
    public function tearDown()
    {
        $this->recordList = null;
        parent::tearDown();
    }

    /**
     * Countable test
     */
    public function testRecordCounting()
    {
        $this->assertEquals(count($this->data), count($this->recordList));
    }


    public function testArrayGet()
    {
        $this->assertEquals('two', $this->recordList[1]);
    }

    public function testAddingArrayElement()
    {
        $this->recordList[] = 'four';
        $this->assertEquals('four', $this->recordList[3]);
    }

    public function testNonScalarAssignment()
    {
        $offset = new \StdClass();
        $offset->index = 1;
        $this->recordList[$offset] = 'four';
        $this->assertEquals('four', $this->recordList[3]);
    }

    public function testUnsetArrayElement()
    {
        unset($this->recordList[1]);
        $this->assertFalse(isset($this->recordList[1]));
    }

    public function testRetrieveIterator()
    {
        $this->assertInstanceOf(
            'ArrayIterator',
            $this->recordList->getIterator()
        );
    }

    public function testChangeIteratorClass()
    {
        $iterator = $this->recordList
            ->setIteratorClass('Slick\Tests\Database\Fixtures\CustomIterator')
            ->getIterator();
        $this->assertInstanceOf(
            'Slick\Tests\Database\Fixtures\CustomIterator',
            $iterator
        );
    }

    public function testInvalidCustomIteratorClass()
    {
        $this->setExpectedException(
            'Slick\Database\Exception\InvalidArgumentException'
        );
        $this->recordList->setIteratorClass('stdClass');
    }

    public function testDataAsArray()
    {
        $this->assertEquals($this->data, $this->recordList->getArrayCopy());
    }

    public function testSerialization()
    {
        $serialized = serialize($this->recordList);
        $this->assertEquals($this->recordList, unserialize($serialized));
    }
}
