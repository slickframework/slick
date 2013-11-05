<?php

/**
 * Collection test case
 * 
 * @package   Test\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Utility;

use Codeception\Util\Stub;
use Slick\Utility\Collection;

/**
 * Collection test case
 * 
 * @package   Test\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CollectionTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Utility\MyCollection The SUT instance
     */
    protected $_collection = null;

    /**
     * Set the collection for tests
     */
    protected function _before()
    {
        parent::_before();
        $this->_collection = new MyCollection(
            array(
                'elements' => array(
                    "one", "two", "three"
                )
            )
        );
    }

    /**
     * Unsets the collection for the nest test
     */
    protected function _after()
    {
        unset($this->_collection);
        parent::_after();
    }

    /**
     * Count collection elements
     * @test
     */
    public function countCollectionItens()
    {
        $this->assertEquals(3, count($this->_collection));
    }

    /**
     * Test serialization on collections
     * @test
     */
    public function useSerialization()
    {
        $txt = serialize($this->_collection);
        $arrTxt = 'C:20:"Utility\MyCollection":50:{a:3:{i:0;s:3:"one";i:1;s:3:"two";i:2;s:5:"three";}}';
        $this->assertEquals($arrTxt, $txt);
        $cl = unserialize($txt);
        $this->assertInstanceOf('\Utility\MyCollection', $cl);
        $this->assertEquals(array("one", "two", "three"), $cl->getElements());
    }

    /**
     * Iterator implementation
     * @test
     */
    public function checkIteratorImplementation()
    {
        $this->assertEquals(0, $this->_collection->key());
        $this->assertTrue($this->_collection->valid());
        $this->assertEquals("one", $this->_collection->current());
        $this->assertInstanceOf(
            '\Utility\MyCollection',
            $this->_collection->next()
        );
        $this->assertTrue($this->_collection->valid());
        $this->assertEquals("two", $this->_collection->current());
        $this->_collection->next()->next();
        $this->assertFalse($this->_collection->valid());
        $this->assertNull($this->_collection->current());
        $this->assertInstanceOf(
            '\Utility\MyCollection',
            $this->_collection->rewind()
        );
        $this->assertEquals(0, $this->_collection->key());
        $this->assertTrue($this->_collection->valid());
    }

    /**
     * Test the add
     * @test
     */
    public function addElementsToCollection()
    {
        $this->assertInstanceOf(
            '\Utility\MyCollection',
            $this->_collection->add("four")
        );
        $this->assertEquals(4, count($this->_collection));
        $expected = array("one", "two", "three", "four");
        $this->assertEquals($expected, $this->_collection->getElements());
    }

    /**
     * Clearing an entire collection
     * @test
     */
    public function clearACollention()
    {
        $this->assertInstanceOf(
            '\Utility\MyCollection',
            $this->_collection->clear()
        );
        $this->assertEquals(0, $this->_collection->key());
        $this->assertFalse($this->_collection->valid());
        $this->assertTrue($this->_collection->isEmpty());
    }

    /**
     * Contains and contains all test.
     * @test
     */
    public function checkCollectionContent()
    {
        $this->assertTrue($this->_collection->contains("one"));
        $this->assertFalse($this->_collection->contains("five"));

        $cl = new MyCollection();
        $cl->add("one")->add("two");
        $this->assertTrue($this->_collection->containsAll($cl));
        $cl->add("five");
        $this->assertFalse($this->_collection->containsAll($cl));

    }

}

/**
 * A test collection
 */
class MyCollection extends Collection
{

}