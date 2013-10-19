<?php

/**
 * Inspector test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Common;

use Slick\Common\Inspector;
use Common\Examples;

/**
 * Use example Car class for tests.
 */
require_once dirname(__FILE__) . '/Examples/Car.php';

/**
 * Inspector class test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class InspectorTest extends \Codeception\TestCase\Test
{

    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    /**
     * @var \Slick\Common\Inspector The SUT object
     */
    protected $_inspector = null;

    /**
     * Sets the inspector used for tests.
     */
    public function _before()
    {
        parent::_before();
        $this->_inspector = new Inspector(new Examples\Car());
    }

    /**
     * Unsets the inspector for the next test.
     */
    public function _after()
    {
        parent::_after();
    }

    /**
     * Inspector creation test.
     * 
     * @test
     */
    public function createAnInspector()
    {
        $inspector = new Inspector('\Common\Examples\Car');
        $fromClass = new Inspector(new Examples\Car());
        $this->assertInstanceOf('\Slick\Common\Inspector', $fromClass);
        $this->assertInstanceOf('\Slick\Common\Inspector', $inspector);
        unset($inspector, $fromClass);
    }

    /**
     * Read the inspected class meta data
     * 
     * @test
     */
    public function readClassMetaData()
    {
        $expected = array(
            '@package' => array('Test\Common\Examples'),
            '@author' => array('Filipe Silva <silvam.filipe@gmail.com>'),
            '@test' => true
        );
        $this->assertEquals($expected, $this->_inspector->getClassMeta());
        $inspector = new Inspector('\Common\Examples\Motor');
        $this->assertEmpty($inspector->getClassMeta());
        unset($inspector);
    }

    /**
     * Retrieving class properties.
     * 
     * @test
     */
    public function readClassProperties()
    {
        $expected = array('_brand', '_model');
        $this->assertEquals($expected, $this->_inspector->getClassProperties());
}
    
    /**
     * Retrieving class methods.
     * 
     * @test
     */
    public function readClassMethods()
    {
        $expected = array('start', 'stop');
        $this->assertEquals($expected, $this->_inspector->getClassMethods());
    }
    
    /**
     * Read property meta data
     * 
     * @test
     */
    public function readPropertyMetaData()
    {
        $expected = array(
            '@var' => array('string The car brand'),
            '@readwrite' => true,
        );
        $this->assertEquals($expected, $this->_inspector->getPropertyMeta('_brand'));
        $this->assertNull($this->_inspector->getPropertyMeta('_model'));
    }
    
    /**
     * Read method meta data
     * 
     * @test
     */
    public function readMethodMetaData()
    {
        $expected = array(
            '@return' => array('boolean The car state'),
            '@throws' => array('\Exception'),
        );
        $this->assertEquals($expected, $this->_inspector->getMethodMeta('start'));
        $this->assertNull($this->_inspector->getMethodMeta('stop'));
    }

}
