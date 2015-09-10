<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Di;

use AbstractContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Slick\Di\Container;
use Slick\Di\Definition\Alias;
use Slick\Di\Definition\Object;
use Slick\Di\Exception;

/**
 * Behat Di Context
 *
 * @package Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DiContext extends AbstractContext implements
    Context, SnippetAcceptingContext
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var mixed
     */
    protected $lastEntry;

    protected $previous;

    protected $exceptionWasThrown = false;

    /**
     * @var \Slick\Di\Definition\Object
     */
    protected $definition;

    /**
     * @var Callback
     */
    protected $callback;

    /**
     * @Given /^I create a container$/
     */
    public function iCreateAContainer()
    {
        $this->container = new Container();
    }

    /**
     * @param mixed $value
     * @param string $entryId
     *
     * @Given /^register a "([^"]*)" under "([^"]*)" key$/
     */
    public function registerAUnderKey($value, $entryId)
    {
        $this->container->register($entryId, $value);
    }

    /**
     * @param $entry
     *
     * @When /^I get "([^"]*)" from container$/
     */
    public function iGetFromContainer($entry)
    {
        try {
            $this->previous = $this->lastEntry;
            $this->lastEntry = $this->container->get($entry);
        } catch (Exception $exp) {
            $this->exceptionWasThrown = true;
        }

    }

    /**
     * @param $expected
     *
     * @Then /^the value should be "([^"]*)"$/
     */
    public function theValueShouldBe($expected)
    {
        \PHPUnit_Framework_Assert::assertEquals($expected, $this->lastEntry);
    }

    /**
     * @Then /^I should get an exception$/
     */
    public function iShouldGetAnException()
    {
        \PHPUnit_Framework_Assert::assertTrue($this->exceptionWasThrown);
    }

    /**
     * @Given /^I define a callable that returns an object$/
     */
    public function iDefineACallableThatReturnsAnObject()
    {
        $this->callback = function($value = null) {
            $obj = new \stdClass();
            $obj->value = $value;
            return $obj;
        };

    }

    /**
     * @param $key
     *
     * @Given /^register it under "([^"]*)" key$/
     */
    public function registerItUnderKey($key)
    {
        if (is_null($this->callback)) {
            $this->definition->name = $key;
            $this->container->register($this->definition);
            return;
        }
        $this->container->register($key, $this->callback);
    }

    /**
     * @Then /^the value should be an object$/
     * @Then /^I should get an object$/
     */
    public function theValueShouldBeAnObject()
    {
        \PHPUnit_Framework_Assert::assertInstanceOf('stdClass', $this->lastEntry);
    }

    /**
     * @param $className
     *
     * @When /^I make class "([^"]*)"$/
     */
    public function iMakeClass($className)
    {
        $this->lastEntry = $this->container->make($className);
    }

    /**
     * @Given /^register an alias for "([^"]*)" as "([^"]*)"$/
     */
    public function registerAnAliasForAs($target, $name)
    {
        $definition = new Alias(['name' => $name, 'target' => $target]);
        $this->container->register($definition);
    }

    /**
     * @Then /^they should be the same object$/
     */
    public function theyShouldBeTheSameObject()
    {
        \PHPUnit_Framework_Assert::assertSame($this->previous, $this->lastEntry);
    }

    /**
     * @param $className
     *
     * @Given /^I create object definition "([^"]*)"$/
     */
    public function iCreateObjectDefinition($className)
    {
        $this->definition = new Object(
            [
                'className' => $className
            ]
        );
    }

    /**
     * @Given /^I set constructor parameters with "([^"]*)"$/
     */
    public function iSetConstructorParametersWith($arg1)
    {
        $this->definition->setConstructArgs([$arg1]);
    }

    /**
     * @Given /^I set property "([^"]*)" to "([^"]*)"$/
     */
    public function iSetPropertyTo($name, $value)
    {
        $this->definition->setProperty($name, $value);
    }

    /**
     * @Then /^the value should be a "([^"]*)" object$/
     */
    public function theValueShouldBeAnObjectOf($className)
    {
        \PHPUnit_Framework_Assert::assertInstanceOf($className, $this->lastEntry);
    }

    /**
     * @When /^I use container to make "([^"]*)"$/
     */
    public function iUseContainerToMakeClass($className)
    {
        $this->previous = $this->lastEntry;
        $this->lastEntry = $this->container->make($className);
    }


}