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
    }

}

/**
 * A test List
 */
class MyList extends AbstractList
{

}