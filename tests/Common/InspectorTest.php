<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common;

use Slick\Common\Inspector;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Inspector Test case
 *
 * @package Slick\tests\Common
 */
class InspectorTest extends TestCase
{

    /**
     * Property for test
     * @var string
     */
    private $property = 'test';

    public function testUniquenessOfInspectors()
    {
        $inspector = Inspector::forClass($this);
        $this->assertSame($inspector, Inspector::forClass($this));
    }

    public function testClassAnnotations()
    {
        $inspector = Inspector::forClass($this);
        $annotations = $inspector->getClassAnnotations();
        $annotation  = $annotations->getAnnotation('@package');
        $this->assertEquals('Slick\tests\Common', $annotation->getValue());
    }

    public function testReadClassProperties()
    {
        $inspector = Inspector::forClass($this);
        $this->assertContains('property', $inspector->getClassProperties());
    }

    public function testPropertyExistence()
    {
        $inspector = Inspector::forClass($this);
        $this->assertTrue($inspector->hasProperty('property'));
    }

    public function testExceptionOnGetUnknownPropertyAnnotations()
    {
        $inspector = Inspector::forClass($this);
        $this->setExpectedException(
            "Slick\\Common\\Exception\\InvalidArgumentException"
        );
        $inspector->getPropertyAnnotations('unknown');
    }

    public function testRetrievePropertyAnnotations()
    {
        $inspector = Inspector::forClass($this);
        $expected = 'string';
        $this->assertEquals(
            $expected,
            $inspector->getPropertyAnnotations('property')
                ->getAnnotation('@var')
                ->getValue()
        );
    }

    public function testGetClassMethods()
    {
        $inspector = Inspector::forClass($this);
        $this->assertContains('tearDown', $inspector->getClassMethods());
    }

    public function testMethodPresence()
    {
        $inspector = Inspector::forClass($this);
        $this->assertTrue($inspector->hasMethod('tearDown'));
    }

    public function testExceptionOnGetUnknownMethodAnnotations()
    {
        $inspector = Inspector::forClass($this);
        $this->setExpectedException(
            "Slick\\Common\\Exception\\InvalidArgumentException"
        );
        $inspector->getMethodAnnotations('unknown');
    }

    /**
     * @tag test, o={"a":1}
     * @test
     */
    public function testGetMethodAnnotations()
    {
        $inspector = Inspector::forClass($this);
        $annotation = $inspector
            ->getMethodAnnotations('testGetMethodAnnotations')
            ->getAnnotation('@tag');
        $this->assertEquals(1, $annotation->getParameter('o')->a);
    }
}