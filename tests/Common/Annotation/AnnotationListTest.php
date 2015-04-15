<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Annotation;

use Slick\Common\Annotation\Basic;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Annotation\AnnotationList;

/**
 * AnnotationList Test case
 *
 * @package Slick\tests\Common\Annotation
 */
class AnnotationListTest extends TestCase
{
    /**
     * @var AnnotationList
     */
    protected $annotationList;

    protected function setup()
    {
        parent::setUp();
        $this->annotationList = new AnnotationList();
    }

    protected function tearDown()
    {
        $this->annotationList = null;
        parent::tearDown();
    }

    /**
     * Invalid annotations for an annotation list
     * @return array
     */
    public function invalidAnnotationsProvider()
    {
        return [
            'object' => [new \stdClass()],
            'number' => [1],
            'string' => ['hello list'],
            'boolean' => [true]
        ];
    }

    /**
     * Trying to append other thing than an annotation raises ana exception
     *
     * @dataProvider invalidAnnotationsProvider
     * @param $annotation
     */
    public function testListAcceptsOnlyAnnotations($annotation)
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\InvalidArgumentException"
        );
        $this->annotationList->append($annotation);
    }

    /**
     * Adding an annotation to the list should return sel instance
     */
    public function testAppendAnnotationToTheList()
    {
        $annotation = new Basic('readwrite', true);
        $this->assertInstanceOf(
            "Slick\\Common\\Annotation\\AnnotationList",
            $this->annotationList->append($annotation)
        );
    }

    /**
     * Check if an annotation exists can have the '@' comment tag prefix
     */
    public function testCheckAnnotationExistence()
    {
        $annotation = new Basic('readwrite', true);
        $this->assertFalse($this->annotationList->hasAnnotation('readwrite'));
        $this->assertTrue(
            $this->annotationList->append($annotation)
                ->hasAnnotation('@readwrite')
        );
    }

    /**
     * Retrieving an annotation will return its reference
     */
    public function testAnnotationRetrieval()
    {
        $annotation = new Basic('readwrite', true);
        $this->annotationList->append($annotation);
        $this->assertSame(
            $annotation,
            $this->annotationList->getAnnotation('@readwrite')
        );
    }

    /**
     * Retrieve an un-existent annotation raises an exception
     */
    public function testExceptionWhenRetrieveUnknownAnnotation()
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\InvalidArgumentException"
        );
        $this->annotationList->getAnnotation('@readwrite');
    }
}
