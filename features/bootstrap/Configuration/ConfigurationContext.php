<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Configuration;

use AbstractContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Slick\Configuration\Configuration;
use Slick\Configuration\ConfigurationInterface;

/**
 * Step definitions for slick/configuration package
 *
 * @behatContext
 */
class ConfigurationContext extends AbstractContext implements
    Context, SnippetAcceptingContext
{

    protected $configFile;

    protected $data = [];

    /** @var  ConfigurationInterface */
    protected $configuration;

    /** @var  string */
    protected $lastKey;

    /**
     * Creates a config file name
     *
     * @param string $fileName
     * @param string $path
     *
     * @Given /^I has the config file "([^"]*)" in "([^"]*)"$/
     */
    public function iHasTheConfigFileIn($fileName, $path)
    {
        $configFile = sys_get_temp_dir().'/'.$path.'/'.$fileName;
        Configuration::addPath(sys_get_temp_dir().'/'.$path);
        touch($configFile);
        $this->configFile = $configFile;
        \PHPUnit_Framework_Assert::assertFileExists($configFile);
    }

    /**
     * Sets a value under provided key in the current file
     *
     * @param string $value
     * @param string $key
     *
     * @Given /^file contains value "([^"]*)" under "([^"]*)" key$/
     */
    public function fileContainsValueUnderKey($value, $key)
    {
        \PHPUnit_Framework_Assert::assertFileExists($this->configFile);

        $this->data[$key] = $value;
        $this->updateFileContent();
        $data = include $this->configFile;
        \PHPUnit_Framework_Assert::assertEquals($this->data, $data);
    }

    /**
     * Creates configuration driver from file name
     *
     * @param string $fileName
     *
     * @When /^I used configuration factory to get "([^"]*)"$/
     */
    public function iUsedConfigurationFactoryToGet($fileName)
    {
        $this->configuration = Configuration::get($fileName);
        \PHPUnit_Framework_Assert::assertInstanceOf(
            'Slick\Configuration\ConfigurationInterface',
            $this->configuration
        );
    }

    /**
     * Check if the provided key exists in data array
     *
     * @param string $key
     *
     * @Then /^I should be able to read "([^"]*)" value$/
     */
    public function iShouldBeAbleToReadValue($key)
    {
        $data = $this->configuration->data;
        \PHPUnit_Framework_Assert::assertArrayHasKey($key, $data);
        $this->lastKey = $key;
    }

    /**
     * Check if the last key used has the provided value
     *
     * @param mixed $value
     *
     * @Given /^it should be equal to "([^"]*)"$/
     */
    public function itShouldBeEqualTo($value)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $value,
            $this->configuration->get($this->lastKey)
        );
    }

    private function updateFileContent()
    {
        $text = [];
        $template = <<<EOT
<?php
return [%s];
EOT;
        $line = "'%s' => '%s',";
        foreach ($this->data as $kye => $value) {
            $text[] = sprintf($line, $kye, $value);
        }
        $content = sprintf($template, implode("\n", $text));
        file_put_contents($this->configFile, $content);
    }
}