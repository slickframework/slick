<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common;

use AbstractContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use PHPUnit_Framework_Assert as PhpUnit;
use Slick\Common\Annotation\AnnotationList;
use Slick\Common\Inspector;

/**
 * Step definitions for slick/common package
 *
 * @behatContext
 */
class CommonContext extends AbstractContext implements
    Context, SnippetAcceptingContext
{
    /**
     * @var Inspector
     */
    protected $inspector;

    /**
     * @var AnnotationList
     */
    protected $classAnnotations;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var AnnotationList
     */
    protected $propertyAnnotations;

    /**
     * @var array
     */
    protected $methods;

    /**
     * @var AnnotationList
     */
    protected $methodAnnotations;

    /**
     * @Given a class with comment blocks
     * @Given have an inspector with it
     */
    public function createInspector()
    {
        $this->inspector = Inspector::forClass($this);
    }

    /**
     * @When I inspect class annotations
     */
    public function classAnnotations()
    {
        $this->classAnnotations = $this->inspector->getClassAnnotations();
    }

    /**
     * @When I inspect class properties
     */
    public function checkPropertyList()
    {
        $this->properties = $this->inspector->getClassProperties();
    }

    /**
     * @When I inspect property ":name" annotations
     * @param $name
     */
    public function checkPropertyAnnotations($name)
    {
        $this->propertyAnnotations = $this->inspector
            ->getPropertyAnnotations($name);
    }

    /**
     * @When I inspect class methods
     */
    public function checkMethodList()
    {
        $this->methods = $this->inspector->getClassMethods();
    }

    /**
     * @When I inspect method ":name" annotations
     * @param $name
     */
    public function checkMethodAnnotations($name)
    {
        $this->methodAnnotations = $this->inspector
            ->getMethodAnnotations($name);
    }

    /**
     * @Then I should get an annotations list of the :type
     * @param $type
     */
    public function checkAnnotationList($type)
    {
        $annotations = $this->getAnnotationsFromType($type);

        PhpUnit::assertInstanceOf(
            'Slick\\Common\\Annotation\\AnnotationList',
            $annotations
        );
    }

    /**
     * @Then :type annotations should contain an annotation named ":name"
     * @param $type
     * @param $name
     */
    public function checkAnnotation($type, $name)
    {
        $annotations = $this->getAnnotationsFromType($type);

        PhpUnit::assertTrue($annotations->hasAnnotation($name));
        PhpUnit::assertInstanceOf(
            'Slick\\Common\\AnnotationInterface',
            $annotations->getAnnotation($name)
        );
    }

    /**
     * @Then I get an array of :type containing ":element"
     * @param $type
     * @param $element
     */
    public function checkArray($type, $element)
    {
        switch ($type) {
            case 'methods':
                $list = $this->methods;
                break;

            case 'properties':
            default:
                $list = $this->properties;
        }

        PhpUnit::assertTrue(is_array($list));
        PhpUnit::assertContains($element, $list);
    }

    /**
     * Returns the property for given type
     *
     * @param $type
     * @return AnnotationList
     */
    protected function getAnnotationsFromType($type)
    {
        switch ($type) {
            case 'property':
                $annotations = $this->propertyAnnotations;
                break;

            case 'method':
                $annotations = $this->methodAnnotations;
                break;

            case 'class':
            default:
                $annotations = $this->classAnnotations;
        }

        return $annotations;
    }
}