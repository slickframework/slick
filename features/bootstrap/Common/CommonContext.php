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
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Common\Fixtures\BaseTest;
use Common\Fixtures\Collection;
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
     * @var BaseTest
     */
    protected $base;

    /**
     * @var mixed
     */
    protected $selectedValue;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var int
     */
    protected $collectionCount;

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

    /**
     * @Given /^class "([^"]*)" implements "AnnotationInterface"$/
     *
     * @param string $className
     */
    public function classImplements($className)
    {
        \PHPUnit_Framework_Assert::assertTrue(
            class_exists($className),
            "Class {$className} does not exists."
        );
    }

    /**
     * @Given /^class "([^"]*)" has dock block with "([^"]*)"$/
     *
     * @param string $className
     */
    public function classHasDockBlockWith($className, $tag)
    {
        $classReflection = new \ReflectionClass($className);
        $comment = $classReflection->getDocComment();
        $expected = <<<EOC
/**
 * Class $className
 *
 * $tag test
 */
EOC;
        \PHPUnit_Framework_Assert::assertEquals($expected, $comment);

    }

    /**
     * @When /^I inspect "([^"]*)" class annotations$/
     *
     * @param string $className
     */
    public function iInspectClassAnnotations($className)
    {
        $this->inspector = Inspector::forClass($className);
    }

    /**
     *  @Then /^I should have a annotations list with "([^"]*)" object$/
     * @param $className
     */
    public function iShouldHaveAAnnotationsListWithObject($className)
    {
        $annotations = $this->inspector->getClassAnnotations();
        \PHPUnit_Framework_Assert::assertTrue($annotations->hasAnnotation($className));
    }

    /**
     * @Given /^I coded a class extending ([^"]*)$/
     */
    public function iCodedAClassExtendingSlickCommonBase($className)
    {
        $this->base = new BaseTest();
        \PHPUnit_Framework_Assert::assertInstanceOf($className, $this->base);
    }

    /**
     * @Given /^class has property "([^"]*)" with "([^"]*)" annotation$/
     */
    public function classHasPropertyWithAnnotation($property, $tag)
    {
        $propertyExists = Inspector::forClass($this->base)
            ->getReflection()
            ->hasProperty($property);

        \PHPUnit_Framework_Assert::assertTrue($propertyExists);
        $annotations = Inspector::forClass($this->base)
            ->getPropertyAnnotations($property);

        \PHPUnit_Framework_Assert::assertTrue($annotations->hasAnnotation($tag));

    }

    /**
     * @When /^I retrieve "([^"]*)" property$/
     */
    public function iRetrieveProperty($property)
    {
        $this->selectedValue = $this->base->$property;
    }

    /**
     * @Then /^I should get "([^"]*)" value$/
     */
    public function iShouldGetValue($expected)
    {
        \PHPUnit_Framework_Assert::assertEquals($expected, $this->selectedValue);
    }

    /**
     * @When /^I set property "([^"]*)" equals to "([^"]*)"$/
     */
    public function iSetPropertyEqualsTo($property, $value)
    {
        $this->base->$property = $value;
    }

    /**
     * @When /^I call "([^"]*)" method$/
     */
    public function iCallMethod($method)
    {
        $this->selectedValue = $this->base->$method();
    }

    /**
     * @When /^I call "([^"]*)" method with "([^"]*)"$/
     */
    public function iCallMethodWith($method, $argument)
    {
        $this->base = $this->base->$method($argument);
    }

    /**
     * @Then /^I should get (true|false) boolean value$/i
     */
    public function iShouldGetBooleanValue($value)
    {
        if ($value == 'true') {
            \PHPUnit_Framework_Assert::assertTrue($this->selectedValue);
        } else {
            \PHPUnit_Framework_Assert::assertFalse($this->selectedValue);
        }
    }

    /**
     * @Given /^I create a collection with elements:$/
     */
    public function iCreateACollectionWithElements(TableNode $table)
    {
        $data = array();
        foreach ($table->getHash() as $hash) {
            $data[] = $hash['value'];
        }
        $this->collection = new Collection($data);
    }

    /**
     * @When /^I use count on the collection$/
     */
    public function iUseCountOnTheCollection()
    {
        $this->collectionCount = count($this->collection);
    }

    /**
     * @Then /^I should have (\d+) elements$/
     */
    public function iShouldHaveElements($elements)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $elements,
            $this->collection->count()
        );
    }

    /**
     * @When /^I clear the collection$/
     */
    public function iClearTheCollection()
    {
        $this->collection->clear();
    }

    /**
     * @Given /^collection isClear is true$/
     */
    public function collectionIsClearIsTrue()
    {
        \PHPUnit_Framework_Assert::assertTrue($this->collection->isEmpty());
    }
}