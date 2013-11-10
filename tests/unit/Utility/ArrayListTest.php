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
use Slick\Utility\ArrayList;

class ArrayListTest extends \Codeception\TestCase\Test
{
   
    /**
     * The SUT object for tests
     * @var \Slick\Utility\ArrayList
     */
    protected $_list = null;

    /**
     * Crete the list
     */
    protected function _before()
    {
        parent::_before();
        $this->_list = new ArrayList();
    }

    /**
     * Clean up properties for next test.
     */
    protected function _after()
    {
        unset($this->_list);
        parent::_after();
    }

    /**
     * Use the list as an array
     * @test
     */
    public function useListAsAarray()
    {
        $this->_list[] = 1;
        $this->assertTrue($this->_list->contains(1));
        $this->_list[0] = 2;
        $this->assertEquals(2, $this->_list[0]);
        $this->assertTrue(isset($this->_list[0]));
        $this->assertFalse(isset($this->_list[3]));
        unset($this->_list[0]);

        $this->assertTrue($this->_list->isEmpty());
    }

    /**
     * Test the arrray access implementation
     * @test
     */
    public function testArrayAccess()
    {
        $arr = new SimpleArray();
        $arr[] = 1;
        $arr[1] = 2;
        $this->assertEquals(1, $arr[0]);
        $this->assertEquals(2, $arr[1]);
        unset($arr[0]);
        $this->assertFalse(isset($arr[0]));
    }

}

class SimpleArray implements \ArrayAccess
{
    protected $_elements = array();

    use \Slick\Utility\Collections\Common\ArrayAccessMethods;
}