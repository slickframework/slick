<?php

/**
 * List test case
 * 
 * @package   Test\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Utility;

use Codeception\Util\Stub;
use Slick\Utility\Collections\AbstractList;

/**
 * List test case
 * 
 * @package   Test\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ListTest extends \Codeception\TestCase\Test
{
    /**
     * The list used for tests
     * @var \Utility\MyList
     */
    protected $_list = null;

    /**
     * Set the SUT list
     */
    protected function _before()
    {
        parent::_before();
        $this->_list = new MyList();
    }

    /**
     * Unsets the list for next test.
     */
    protected function _after()
    {
        unset($this->_list);
        parent::_after();
    }

    /**
     * Use as an arary
     * @test
     */
    public function useListAsAnArray()
    {
        $this->_list[] = "one";
        $this->assertEquals("one", $this->_list[0]);
        $this->_list[1] = "one";
        $this->assertEquals(2, count($this->_list));
        $this->assertEquals("one", $this->_list[1]);
        $this->assertTrue(isset($this->_list[1]));
        unset($this->_list[1]);
        $this->assertFalse(isset($this->_list[1]));
    }

    /**
     * Try to add elements to the list
     * @test
     * @expectedException \Slick\Utility\Exception\InvalidArgumentException
     * @exceptedExceptionMessage Index must be a zero based positive integer.
     */
    public function addElementsToList()
    {
        $this->_list->add("two");
        $this->assertEquals("two", $this->_list[0]);
        $this->_list->add("one", 0);
        $this->assertEquals("one", $this->_list[0]);
        try {
            $this->_list->add('six', 5);
            $this->fail("An index out of bounds exception should be throwed here");
        } catch (\Slick\Utility\Exception\IndexOutOfBoundsException $e) {
            $this->assertEquals(
                "The index '5' is not in between 0 and 2",
                $e->getMessage()
            );
        }
        $this->_list->add('test', '');
    }

    /**
     * Tries to add a collection to the list
     * @test
     */
    public function addACollection()
    {
        $this->_list->setElements(array("one", "four", "five"));
        $cl = new MyList(
            array(
                'elements' => array("two", "three")
            )
        );
        $expected = array("one", "two", "three", "four", "five");
        $this->assertEquals($expected, $this->_list->getElements());

    }

}

/**
 * A test List
 */
class MyList extends AbstractList
{

}