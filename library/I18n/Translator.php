<?php

/**
 * This file is part of slick/i18n package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\I18n;

use Slick\Common\BaseMethods;
use Zend\I18n\Translator\Translator as ZendTranslator;

/**
 * Translator (I18n)
 *
 * @package Slick\I18n
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property ZendTranslator  $translatorService
 *
 * @property string $type
 * @property string $basePath
 * @property string $domain
 * @property string $fallbackLocale
 *
 * @method Translator setBasePath(string $basePath)
 * @method Translator setDomain(string $domainName)
 * @method Translator setType(string $type)
 * @method Translator setLocale(string $locale)
 *
 * @method string getLocale()
 * @method string getDomain()
 * @method string getBasePath()
 */
class Translator
{

    const TYPE_PHP_ARRAY = 'phparray';
    const TYPE_GETTEXT   = 'gettext';

    /**
     * @readwrite
     * @var ZendTranslator
     */
    protected $translatorService;

    /**
     * @readwrite
     * @var string The message domain
     */
    protected $domain = 'default';

    /**
     * @readwrite
     * @var string
     */
    protected $basePath = './I18n';

    /**
     * @readwrite
     * @var string
     */
    protected $type = self::TYPE_PHP_ARRAY;

    /**
     * @readwrite
     * @var string
     */
    protected $locale = 'en_US';

    /**
     * @var array
     */
    private $types = [
        self::TYPE_GETTEXT   => '.mo',
        self::TYPE_PHP_ARRAY => '.php'
    ];

    /**
     * @var Translator
     */
    private static $instance;

    /**
     * @var array
     */
    private $loadedFiles = [];

    /**
     * Trait with method for base class
     */
    use BaseMethods;

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     *
     * @param array $options A list of properties for this connector
     */
    protected function __construct($options = array())
    {
        $this->hydrate($options);
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Lazy loads zend translator
     *
     * @return ZendTranslator
     */
    public function getTranslatorService()
    {
        if (is_null($this->translatorService)) {
            $this->translatorService = new ZendTranslator();
        }
        return $this->translatorService;
    }

    /**
     * Returns the messages file name based on domain
     *
     * @param string $domain
     * @param string $locale
     *
     * @return array
     */
    protected function loadFile($domain = null, $locale = null)
    {
        $domain = $domain ?: $this->domain;
        $locale = $locale ?: $this->locale;
        $key = "{$this->basePath}::{$this->type}::{$locale}::{$domain}";

        if (!array_key_exists($key, $this->loadedFiles)) {
            $name = "{$domain}{$this->types[$this->type]}";
            $this->loadedFiles[$key] = $name;

            $this->getTranslatorService()->addTranslationFilePattern(
                $this->type,
                $this->basePath,
                "%s/{$name}",
                $domain
            );
        }

        return [$domain, $locale];
    }

    /**
     * Returns the translation for the provided message
     *
     * @param string $message
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    public function translate($message, $domain = null, $locale = null)
    {
        list($domain, $locale) = $this->loadFile($domain, $locale);
        return $this->getTranslatorService()
            ->translate($message, $domain, $locale);
    }

    /**
     * Translate a plural message.
     *
     * @param string $singular
     * @param string $plural
     * @param int    $number
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    public function translatePlural(
        $singular, $plural, $number,$domain = null, $locale = null
    ) {
        list($domain, $locale) = $this->loadFile($domain, $locale);
        return $this->getTranslatorService()
            ->translatePlural($singular, $plural, $number, $domain, $locale);
    }

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @param array $options The list of property values of this instance.
     *
     * @return Translator The *Singleton* instance.
     */
    public static function getInstance($options = array())
    {
        if (is_null(self::$instance)) {
            self::$instance = new Translator($options);
        }
        return self::$instance;
    }
}
