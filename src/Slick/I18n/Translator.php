<?php

/**
 * Translator (I18n)
 *
 * @package   Slick\I18n
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\I18n;

use Slick\Common\Inspector,
    Slick\Common\BaseSingleton,
    Slick\Configuration\Configuration,
    Slick\Configuration\Driver\DriverInterface;
use Zend\I18n\Translator\Translator as ZendTranslator;

/**
 * Translator (I18n)
 *
 * @package   Slick\I18n
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property DriverInterface $configuration
 * @property ZendTranslator $translatorService
 * @property string $type
 * @property string $basePath
 * @property string $domain
 * @property string $fallbackLocale
 */
class Translator extends BaseSingleton
{

    /**
     * @readwrite
     * @var ZendTranslator
     */
    protected $_translatorService;

    /**
     * @readwrite
     * @var DriverInterface
     */
    protected $_configuration;

    /**
     * @readwrite
     * @var string The message domain
     */
    protected $_domain = 'default';

    /**
     * @readwrite
     * @var string Default fallback locale
     */
    protected $_fallbackLocale = 'en_US';

    /**
     * @readwrite
     * @var string
     */
    protected $_basePath = './I18n';

    /**
     * @readwrite
     * @var string
     */
    protected $_type = 'gettext';

    /**
     * @var array
     */
    private $_types = [
        'gettext' => '.mo',
        'phparray' => '.php'
    ];

    /**
     * @var Translator
     */
    private static $_instance;

    /**
     * Lazy loads configuration
     *
     * @return DriverInterface
     */
    public function getConfiguration()
    {
        if (is_null($this->_configuration)) {
            $this->_configuration = Configuration::get('config');
        }
        return $this->_configuration;
    }

    /**
     * Lazy loads zend translator
     *
     * @return ZendTranslator
     */
    public function getTranslatorService()
    {
        if (is_null($this->_translatorService)) {
            $translator = new ZendTranslator();
            $translator->addTranslationFilePattern(
                $this->type,
                $this->basePath,
                '%s/'.$this->getMessageFile(),
                $this->domain
            );
            $this->_translatorService = $translator;
        }
        return $this->_translatorService;
    }

    /**
     * Returns the messages file name based on domain
     *
     * @return string
     */
    public function getMessageFile()
    {
        $name = $this->domain;
        $name .= $this->_types[$this->type];
        return $name;
    }

    /**
     * Returns the translation for the provided message
     *
     * @param string $message
     *
     * @return string
     */
    public function translate($message)
    {
        $locale = $this->configuration
            ->get('i18n.locale', $this->fallbackLocale);

        return $this->getTranslatorService()
            ->translate($message, $this->domain, $locale);
    }

    /**
     * Translate a plural message.
     *
     * @param  string $singular
     * @param  string $plural
     * @param  int    $number
     *
     * @return string
     */
    public function translatePlural($singular, $plural, $number)
    {
        $locale = $this->configuration->
            get('i18n.locale', $this->fallbackLocale);
        return $this->getTranslatorService()
            ->translatePlural(
                $singular,
                $plural,
                $number,
                $this->domain,
                $locale
            );
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
        if (is_null(static::$_instance)) {
            static::$_instance = new Translator($options);
        }
        return static::$_instance;
    }

    /**
     * Sets locale
     *
     * @param $locale
     *
     * @returns Translator
     */
    public function setLocale($locale)
    {
        $this->getConfiguration()->set('i18n.locale', $locale);
        return $this;
    }
}