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

        /** @var Inspector\Annotation[]|Inspector\AnnotationsList $result */
        $result = $this->_inspector->getClassAnnotations();
        $this->assertInstanceOf('Slick\Common\Inspector\AnnotationsList', $result);
        $this->assertTrue($result['package']->getValue() == 'Test\Common\Examples');
        $this->assertTrue($result['author']->getValue() == 'Filipe Silva <silvam.filipe@gmail.com>');
        $this->assertTrue($result['test']->getValue());

        $this->assertTrue($result->hasAnnotation('@author'));

        $this->assertFalse($result->hasAnnotation('@read'));
        
        $inspector = new Inspector('\Common\Examples\Motor');
        $this->assertTrue(count($inspector->getClassAnnotations()) == 0);
        unset($inspector);
    }

    /**
     * Retrieving class properties.
     * 
     * @test
     */
    public function readClassProperties()
    {
        $expected = ['_brand', '_model'];
        $this->assertEquals($expected, $this->_inspector->getClassProperties());
}
    
    /**
     * Retrieving class methods.
     * 
     * @test
     */
    public function readClassMethods()
    {
        $expected = ['start', 'stop'];
        $this->assertEquals($expected, $this->_inspector->getClassMethods());
    }
    
    /**
     * Read property meta data
     * 
     * @test
     * @expectedException \Slick\Common\Exception\InvalidArgumentException
     */
    public function readPropertyMetaData()
    {
        /** @var Inspector\Annotation[]|Inspector\AnnotationsList $result */
        $result = $this->_inspector->getPropertyAnnotations('_brand');
        $this->assertInstanceOf('Slick\Common\Inspector\AnnotationsList', $result);
        $this->assertTrue($result->hasAnnotation('var'));
        $this->assertTrue($result['readwrite']->getValue());

        $tag = $result['hasMany'];
        $this->assertEquals('test', $tag->getParameter('table'));
        $this->assertEquals('my_brand', $tag->getParameter('foreignKey'));
        $this->assertNull($tag->getParameter('other'));
        $this->assertTrue(count($this->_inspector->getPropertyAnnotations('_model')) == 0);

        $this->_inspector->getPropertyAnnotations('_unknown');
    }
    
    /**
     * Read method meta data
     * 
     * @test
     * @expectedException \Slick\Common\Exception\InvalidArgumentException
     */
    public function readMethodMetaData()
    {
        /** @var Inspector\AnnotationsList|Inspector\AnnotationInterface[] $tags */
        $tags = $this->_inspector->getMethodAnnotations('start');
        $this->assertEquals('\Exception', $tags->getAnnotation('@throws')->getValue());
        $this->assertEquals('boolean The car state', $tags['return']->getValue());
        $this->assertEquals(0, count($this->_inspector->getMethodAnnotations('stop')));
        $this->_inspector->getMethodAnnotations('_unknown');
    }

    /**
     * Check if only the Tag can be added to a tag list
     * @test
     * @expectedException \Slick\Common\Exception\InvalidArgumentException
     */
    public function checkTagListAppend()
    {
        $tgl = new Inspector\AnnotationsList();
        $tgl[] = "Hello exception!";
    }

}
