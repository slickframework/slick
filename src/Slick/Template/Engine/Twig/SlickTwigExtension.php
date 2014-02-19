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

use Zend\Http\PhpEnvironment\Request;

/**
 * SlickTwigExtension
 *
 * @package   Slick\Template\Engine
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SlickTwigExtension extends \Twig_Extension
{

    /**
     * @var Request HTTP Request object
     */
    protected $_request;

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
            )
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
            'basePath' => $this->getRequest()->getBasePath()
        ];
    }

    /**
     * Set response object
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
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