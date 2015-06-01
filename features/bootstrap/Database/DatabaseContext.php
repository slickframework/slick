<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Database;

use AbstractContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\RecordList;


/**
 * Step definitions for slick/database package
 *
 * @behatContext
 */
class DatabaseContext extends AbstractContext implements
    Context, SnippetAcceptingContext
{

    /**
     * @var array Adapter options
     */
    protected $factoryOptions = [];

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var int|RecordList
     */
    protected $result;

    /**
     * @Given /^a database server with:$/
     */
    public function setDatabaseOptions(TableNode $options)
    {
        $row = $options->getIterator()->current();
        $this->factoryOptions = [
            'driver' => $row['driver'],
            'options' => json_decode($row['options'], true)
        ];
    }

    /**
     * @When /^I create adapter using a factory object$/
     */
    public function createAdapter()
    {
        $this->adapter = Adapter::create($this->factoryOptions);
    }

    /**
     * @Then /^I should be able to run query "([^"]*)"$/
     */
    public function runQuery($sql)
    {
        $this->result = $this->adapter->query($sql);
    }

    /**
     * @Then /^I should be able to execute query:$/
     */
    public function executeQuery(PyStringNode $sql)
    {
        $this->result = $this->adapter->execute($sql->getRaw());
    }

    /**
     * @Given /^affected rows should be "([^"]*)"$/
     */
    public function affectedRowsShouldBe($expected)
    {
        \PHPUnit_Framework_Assert::assertEquals($expected, (string) $this->result);
    }

    /**
     * @Given /^I execute query:$/
     */
    public function iExecuteQuery(PyStringNode $string)
    {
        $this->executeQuery($string);
    }

    /**
     * @When /^I run query:$/
     */
    public function iRunQuery(PyStringNode $string)
    {
        $this->runQuery($string->getRaw());
    }

    /**
     * @Then /^I should get (\d+) records$/
     */
    public function iShouldGetRecords($arg1)
    {
        \PHPUnit_Framework_Assert::assertEquals($arg1, $this->result->count());
    }
}