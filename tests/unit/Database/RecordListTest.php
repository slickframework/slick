<?php

/**
 * Record list test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database;

use Slick\Database\RecordList;

/**
 * Record list test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RecordListTest extends \Codeception\TestCase\Test
{

    /**
     * Check that record list is countable
     * @test
     */
    public function countRecordsInTheList()
    {
        $list = new RecordList(['data' => [1, 2, 4]]);
        $this->assertInstanceOf('Countable', $list);
        $this->assertEquals(3, count($list));
        $this->assertFalse(empty($list));
    }

    /**
     * Try to use the record list as an array
     * @test
     */
    public function useRecordListAsArray()
    {
        $data = ['one', 'two', 'three'];
        $list = new RecordList(['data' => $data]);
        $this->assertEquals('two', $list[1]);
        $this->assertTrue(isset($list[2]));
        unset($list[0]);
        $this->assertFalse(isset($list[0]));
        $list[] = 'four';
        $list[0] = 'one';
        /** @var RecordList $list */
        $this->assertTrue(isset($list[0]));
        $this->assertEquals('four', $list[3]);
        $this->assertTrue(is_array($list->getArrayCopy()));
    }

    /**
     * Retrieve an iterator or traversable
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function useRecordListAsIterator()
    {
        $data = ['one', 'two', 'three'];
        $list = new RecordList(['data' => $data]);
        $itr = $list->getIterator();
        $this->assertInstanceOf('Traversable', $itr);
        $this->assertInstanceOf('Iterator', $itr);

        foreach ($list as $key => $number) {
            $this->assertEquals(
                $data[$key],
                $number
            );
        }

        $list->setIteratorClass('RecursiveArrayIterator');
        $itr = $list->getIterator();
        $this->assertInstanceOf('RecursiveArrayIterator', $itr);

        $list->setIteratorClass('stdClass');
    }

    /**
     * Serialize a record List
     * @test
     */
    public function serializeARecordList()
    {
        $data = ['one', 'two', 'three'];
        $list = new RecordList(['data' => $data]);

        $serialized = serialize($list);

        /** @var RecordList $listUnserialized */
        $listUnserialized = unserialize($serialized);
        $this->assertEquals($list->getIterator(), $listUnserialized->getIterator());
    }
}