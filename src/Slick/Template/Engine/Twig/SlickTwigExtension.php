<?php

/**
 * SlickTwigExtension
 *
 * @package   Slick\Template\Engine
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Template\Engine\Twig;

use Twig_Extension;
use Twig_Environment;
use Twig_SimpleFilter;
use Slick\Version\Version;
use Slick\I18n\TranslateMethods;
use Zend\Http\PhpEnvironment\Request;

/**
 * SlickTwigExtension
 *
 * @package   Slick\Template\Engine
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SlickTwigExtension extends Twig_Extension
{

    /**
     * @var Request HTTP Request object
     */
    protected $_request;

    /**
     * Adds translate methods to this class
     */
    use TranslateMethods;

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'slick';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            // addCss
            new \Twig_SimpleFunction(
                'addCss',
                function($name, $folder = 'css') {
                    return $this->addLinkRef($name, $folder);
                }
            ),

            // addJs
            new \Twig_SimpleFunction(
                'addJs',
                function($name, $folder = 'js') {
                    return $this->addLinkRef($name, $folder);
                }
            ),

            // url
            new \Twig_SimpleFunction(
                'url',
                function($name) {
                    return $this->addLinkRef($name);
                }
            ),

            new \Twig_SimpleFunction(
                'translate',
                function($message) {
                    return $this->translate($message);
                }
            ),

            new \Twig_SimpleFunction(
                'transPlural',
                function($singular, $plural, $number) {
                    return $this->translatePlural($singular, $plural, $number);
                }
            ),
        ];
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [
            'basePath' => $this->getRequest()->getBasePath(),
            'slickVersion' => Version::VERSION
        ];
    }

    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter(
                'truncate',
                '\Slick\Template\Engine\Twig\truncateFilter',
                ['needs_environment' => true]
            ),
            new Twig_SimpleFilter(
                'wordwrap',
                '\Slick\Template\Engine\Twig\wordwrapFilter',
                ['needs_environment' => true]
            ),
        ];
    }

    /**
     * Lazy load of the HTTP response object
     *
     * @return Request
     */
    public function getRequest()
    {
        if (is_null($this->_request)) {
            $this->_request = new Request();
        }
        return $this->_request;
    }

    protected function addLinkRef($name, $folder = null)
    {
        $base = $this->getRequest()->getBasePath();
        $folder = ($folder) ? trim($folder, '/') . '/': '';
        $path = "{$base}/{$folder}{$name}";
        return $path;
    }
}

function wordwrapFilter(
    Twig_Environment $env, $value, $length = 80, $separator = "\n",
    $preserve = false)
{
    return wordwrap($value, $length, $separator, !$preserve);
}

function truncateFilter(
    Twig_Environment $env, $value, $length = 30, $preserve = false,
    $separator = '...')
{
    if (function_exists('mb_get_info')) {
        return truncateFilterMbInfo(
            $env,
            $value,
            $length,
            $preserve,
            $separator
        );
    }

    return truncateFilterNoMbInfo(
        $env,
        $value,
        $length,
        $preserve,
        $separator
    );
}

function truncateFilterMbInfo(
    Twig_Environment $env, $value, $length = 30, $preserve = false,
    $separator = '...')
{
    if (mb_strlen($value, $env->getCharset()) > $length) {
        if ($preserve) {
            // If breakpoint is on the last word, return the
            // value without separator.
            if (
                false === (
                $breakpoint = mb_strpos(
                    $value,
                    ' ',
                    $length,
                    $env->getCharset()
                )
                )
            ) {
                return $value;
            }

            $length = $breakpoint;
        }

        return rtrim(
            mb_substr($value, 0, $length, $env->getCharset())
        ) . $separator;
    }

    return $value;
}

function truncateFilterNoMbInfo(
    Twig_Environment $env, $value, $length = 30, $preserve = false,
    $separator = '...')
{
    if (strlen($value) > $length) {
        if ($preserve) {
            if (false !== ($breakpoint = strpos($value, ' ', $length))) {
                $length = $breakpoint;
            }
        }

        return rtrim(substr($value, 0, $length)) . $separator;
    }

    return $value;
}

