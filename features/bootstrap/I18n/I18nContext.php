<?php

/**
 * This file is part of slick/i18n package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace I18n;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Slick\I18n\Translator;
use PHPUnit_Framework_Assert as Assert;

/**
 * Step definitions for slick/i18n package
 *
 * @behatContext
 */
class I18nContext extends \AbstractContext implements
    Context, SnippetAcceptingContext
{

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $lastTranslation;

    /**
     * @var array
     */
    protected $types = [
        'php' => Translator::TYPE_PHP_ARRAY,
        'gettext' => Translator::TYPE_GETTEXT
    ];

    /**
     * Create translator with type and locale
     *
     * @Given /^I have a "([^"]*)" messages file for "([^"]*)" locale$/
     *
     * @param string $type
     * @param string $locale
     */
    public function createTranslator($type, $locale)
    {
        $this->translator = Translator::getInstance(
            [
                'basePath' => __DIR__ . '/lang'
            ]
        )
            ->setType($this->types[$type])
            ->setLocale($locale);
    }

    /**
     * Translate a given message
     *
     * @When /^I translate "([^"]*)"$/
     *
     * @param $message
     */
    public function translate($message)
    {
        $this->lastTranslation = $this->translator->translate($message);
    }

    /**
     * Check last translation result
     *
     * @Then /^translation should be "([^"]*)"$/
     *
     * @param string $expected
     */
    public function checkTranslation($expected)
    {
        Assert::assertEquals($expected, $this->lastTranslation);
    }

    /**
     * Translate plural
     *
     * @When /^I translate "([^"]*)" plural "([^"]*)" with (\d+) as count$/
     *
     * @param string  $singular
     * @param string  $plural
     * @param integer $count
     */
    public function translatePlural($singular, $plural,  $count)
    {
        $this->lastTranslation = $this->translator
            ->translatePlural($singular, $plural, $count);
    }

    /**
     * Request translation on given domain
     *
     * @When /^I translate "([^"]*)" on "([^"]*)" domain$/
     *
     * @param string $message
     * @param string $domain
     */
    public function translateOnDomain($message, $domain)
    {
        $this->lastTranslation = $this->translator
            ->translate($message, $domain);
    }
}