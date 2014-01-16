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

use Slick\Common\Inspector,
    Slick\Common\Inspector\TagList;
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

        $result = $this->_inspector->getClassMeta();
        $this->assertInstanceOf('Slick\Common\Inspector\TagList', $result);
        $this->assertTrue($result['@package']->value == 'Test\Common\Examples');
        $this->assertTrue($result['@author']->value == 'Filipe Silva <silvam.filipe@gmail.com>');
        $this->assertTrue($result['@test']->value);

        $this->assertTrue($result->hasTag('@author'));

        $this->assertFalse($result->getTag('@read'));
        
        $inspector = new Inspector('\Common\Examples\Motor');
        $this->assertTrue(count($inspector->getClassMeta()) == 0);
        unset($inspector);
    }

    /**
     * Retrieving class properties.
     * 
     * @test
     */
    public function readClassProperties()
    {
        $expected = new \ArrayIterator(array('_brand', '_model'));
        $this->assertEquals($expected, $this->_inspector->getClassProperties());
}
    
    /**
     * Retrieving class methods.
     * 
     * @test
     */
    public function readClassMethods()
    {
        $expected = new \ArrayIterator(array('start', 'stop'));
        $this->assertEquals($expected, $this->_inspector->getClassMethods());
    }
    
    /**
     * Read property meta data
     * 
     * @test
     * @expectedException Slick\Common\Exception\InvalidArgumentException
     */
    public function readPropertyMetaData()
    {
        $result =  $this->_inspector->getPropertyMeta('_brand');
        $this->assertInstanceOf('Slick\Common\Inspector\TagList', $result);
        $this->assertTrue($result->hasTag('@var'));
        $this->assertTrue($result['@readwrite']->value);
        $tag = $result['@hasMany'];
        $this->assertEquals('test', $tag->value['table']);
        $this->assertEquals('my_brand', $tag->getForeignKey());
        $this->assertNull($tag->getOtherName());
        $this->assertTrue(count($this->_inspector->getPropertyMeta('_model')) == 0);

        $this->_inspector->getPropertyMeta('_unknown');
    }
    
    /**
     * Read method meta data
     * 
     * @test
     * @expectedException Slick\Common\Exception\InvalidArgumentException
     */
    public function readMethodMetaData()
    {
        $tags = $this->_inspector->getMethodMeta('start');
        $this->assertEquals('\Exception', $tags->getTag('@throws')->value);
        $this->assertEquals('boolean The car state', $tags->getTag('@return')->value);
        $this->assertEquals(0, count($this->_inspector->getMethodMeta('stop')));
        $this->_inspector->getMethodMeta('_unknown');
    }

    /**
     * Check if only the Tag can be added to a tag list
     * @test
     * @expectedException Slick\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage Only a Slick\Common\Inspector\Tag object can be added to a TagList
     */
    public function checkTagListAppend()
    {
        $tgl = new TagList();
        $tgl[] = "Hello exception!";
    }

}
