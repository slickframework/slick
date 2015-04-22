<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Annotation;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Annotation\Factory;
use Slick\Tests\Common\Annotation\Fixtures\Annotation as TestAnnotation;

/**
 * FactoryTest test case
 *
 * @package Slick\Tests\Common\Annotation
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class FactoryTest extends TestCase 
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * Creates SUT factory object
     */
    protected function setup()
    {
        parent::setup();
        $reflection = new \ReflectionClass('stdClass');
        $this->factory = new Factory();
        $this->factory->setReflection($reflection);
    }

    /**
     * Cleanup for next test
     */
    protected function tearDown()
    {
        $this->factory = null;
        parent::tearDown();
    }

    /**
     * Creates a basic annotation
     */
    public function testCreateBasicAnnotation()
    {
        $comment = <<<EOC
/**
 * Test
 *
 * @readwrite
 */
EOC;
        $annotations = $this->factory->getAnnotationsFor($comment);
        $annotation = $annotations->getAnnotation('@readwrite');
        $this->assertInstanceOf(
            "Slick\\Common\\Annotation\\Basic",
            $annotation
        );

    }

    public function testCreateCustomAnnotation()
    {
        $comment = <<<EOC
/**
 * Test
 *
 * @Slick\Tests\Common\Annotation\Fixtures\Annotation
 */
EOC;
        $annotations = $this->factory->getAnnotationsFor($comment);
        $annotation = $annotations->getAnnotation(
            'Slick\Tests\Common\Annotation\Fixtures\Annotation'
        );
        $this->assertInstanceOf(
            "Slick\\Common\\Annotation\\Basic",
            $annotation
        );
    }

    public function testInvalidAnnotationClass()
    {
        $comment = <<<EOC
/**
 * Test
 *
 * @Slick\Tests\Common\Annotation\Fixtures\Invalid
 */
EOC;
        $this->setExpectedException(
            "Slick\\Common\\Exception\\InvalidAnnotationClassException"
        );
        $annotations = $this->factory->getAnnotationsFor($comment);
    }

    /**
     * A class with use statements that loads the annotations evaluating
     * the namespaces and alias
     */
    public function testGetCustomClassFromAlias()
    {
        $comment = <<<EOC
/**
 * Test
 *
 * @TestAnnotation
 */
EOC;
        $this->factory->setReflection(new \ReflectionClass($this));
        $annotations = $this->factory->getAnnotationsFor($comment);
        $annotation = $annotations->getAnnotation(
            'Slick\Tests\Common\Annotation\Fixtures\Annotation'
        );
        $this->assertInstanceOf(
            "Slick\\Common\\Annotation\\Basic",
            $annotation
        );

    }
}
