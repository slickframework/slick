<?php

/**
 * Custom annotation test case
 *
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.1.0
 */

namespace Common;
use Codeception\Module\Slick;
use Codeception\Util\Stub;
use Slick\Common\Inspector;
use Slick\Common\Inspector\Annotation;
use Slick\Common\Inspector\AnnotationInterface;

/**
 * Custom annotation test case
 *
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class AnnotationTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function _before()
    {
        Inspector::addAnnotationClass('testColumn', 'Common\Column');
    }

    /**
     * Initiate a custom annotation
     * @test
     * @expectedException \Slick\Common\Exception\InvalidArgumentException
     */
    public function initiateACustomAnnotation()
    {
        $inspector = new Inspector('Common\MyMockClass');
        /** @var Inspector\AnnotationsList|Annotation[] $annotations */
        $annotations = $inspector->getPropertyAnnotations('_test');
        $this->assertTrue($annotations->hasAnnotation('testColumn'));
        $this->assertEquals('Other\Class', $annotations['testColumn']->getValue());
        $this->assertTrue($annotations->getAnnotation('testColumn')->getParameter('primaryKey'));
        $this->assertEquals('test_id', $annotations->getAnnotation('testColumn')->getParameter('foreignKey'));

        $otherTest = $inspector->getPropertyAnnotations('_anotherTest')->getAnnotation('myTest');
        $this->assertEquals(2.9, $otherTest->getParameter('value'));

        $annotations->getAnnotation('_unknown_');
    }

    /**
     * Add invalid class to the class map
     * @test
     * @expectedException \Slick\Common\Exception\InvalidArgumentException
     */
    public function addInvalidClass()
    {
        Inspector::addAnnotationClass('test', '\Common\MyMockClass');
    }

}

class Column extends Annotation implements AnnotationInterface
{

    protected $_parameters = [
        'primaryKey' => false
    ];
}

class MyMockClass
{

    /**
     * @testColumn Other\Class, type=string, primaryKey, foreignKey="test_id", foo=[1, 2, 3], bar={"test":1,"other":[1,2]}
     * @var string
     */
    protected $_test;

    /**
     * @myTest foo=true, bar=false, value=2.9
     * @var string
     */
    protected $_anotherTest;
}