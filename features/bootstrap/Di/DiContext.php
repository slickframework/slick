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

    protected $exceptionWasThrown = false;

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
            $this->lastEntry = $this->container->get($entry);
        } catch (\Slick\Di\Exception $exp) {
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
}