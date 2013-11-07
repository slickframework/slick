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
     * Create a list
     * @test
     */
    public function creatAList()
    {
        $this->assertInstanceOf(
            'Slick\Utility\Collections\ListInterface',
            $this->_list
        );
    }

    /**
     * Adding an element to an expecific location
     * @test
     * @expectedException \Slick\Utility\Exception\IndexOutOfBoundsException
     * @expectedExceptionMessage The index '10' is out of this list bounds.
     */
    public function addElementToEspecificPosition()
    {
        $this->_list->setElements(array(2, 3, 5));
        $this->assertTrue($this->_list->add(6));
        $expected = array(2, 3, 5, 6);
        $this->assertEquals($expected, $this->_list->getElements());

        $this->assertTrue($this->_list->add(4, 2));
        $expected = array(2, 3, 4, 5, 6);
        $this->assertEquals($expected, $this->_list->getElements());

        $this->assertTrue($this->_list->add(1, 0));
        $expected = array(1, 2, 3, 4, 5, 6);
        $this->assertEquals($expected, $this->_list->getElements());

        try {
            $this->_list->add(8, "five");
            $this->fail("This should raise an exception here.");
        } catch(\Slick\Utility\Exception $e) {
            $this->assertInstanceOf('\ErrorException', $e);
            $this->assertInstanceOf(
                '\Slick\Utility\Exception\InvalidArgumentException',
                $e
            );
            $expected = "Index must be a valid, zero base integer or null.";
            $this->assertEquals($expected, $e->getMessage());
        }

        $this->_list->add(8, 10);
    }

    /**
     * Add an elements collection to a list
     * @test
     * @expectedException \Slick\Utility\Exception\IndexOutOfBoundsException
     * @expectedExceptionMessage The index '20' is out of this list bounds.
     */
    public function addCollectionToList()
    {
        $this->_list->setElements(array(2, 3, 5));
        $list = clone($this->_list);
        $this->assertTrue($this->_list->addAll($list));
        $expected = array(2, 3, 5, 2, 3, 5);
        $this->assertEquals($expected, $this->_list->elements);

        $this->_list = clone($list);
        $this->assertTrue($this->_list->addAll($list, 2));
        $expected = array(2, 3, 2, 3, 5, 5);
        $this->assertEquals($expected, $this->_list->elements);

        try {
            $this->_list->addAll($list, "");
            $this->fail("This should raise an exception here.");
        } catch(\Slick\Utility\Exception $e) {
            $this->assertInstanceOf('\ErrorException', $e);
            $this->assertInstanceOf(
                '\Slick\Utility\Exception\InvalidArgumentException',
                $e
            );
            $expected = "Index must be a valid, zero base integer or null.";
            $this->assertEquals($expected, $e->getMessage());
        }

        $this->_list->addAll($list, 20);
    }

}

/**
 * A test List
 */
class MyList extends AbstractList
{

}