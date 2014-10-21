<?php

/**
 * MVC Controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use Slick\Common\Base;
use Slick\I18n\TranslateMethods;
use Zend\Http\Header\GenericHeader;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Slick\Mvc\Libs\Session\FlashMessageMethods;

/**
 * MVC Controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Request $request HTTP request object
 * @property Response $response HTTP response object
 * @property bool $renderLayout Flag for layout rendering
 * @property bool $renderView Flag for view rendering
 * @property string $layout Layout file name
 * @property string $view View file name
 * @property bool $scaffold
 *
 * @property-read array $viewVars A key/value pair of data for view rendering
 *
 * @method array getViewVars() Returns the data array for view rendering
 * @method Request getRequest() Returns the HTTP request object
 * @method Controller setRequest(Request $request) Sets the HTTP request object
 * @method Response getResponse() Returns the HTTP response object
 * @method Controller setResponse(Response $response) Sets HTTP response object
 * @method bool isScaffold() Returns true if controller is scaffolding
 */
class Controller extends Base
{

    /**
     * @read
     * @var array
     */
    protected $_viewVars = [];

    /**
     * @readwrite
     * @var Request
     */
    protected $_request;

    /**
     * @readwrite
     * @var Response
     */
    protected $_response;

    /**
     * @readwrite
     * @var bool
     */
    protected $_renderLayout = true;

    /**
     * @readwrite
     * @var bool
     */
    protected $_renderView = true;

    /**
     * @read
     * @var bool
     */
    protected $_scaffold = false;

    /**
     * @readwrite
     * @var View
     */
    protected $_layout;

    /**
     * @readwrite
     * @var View
     */
    protected $_view;

    /**
     * Adds translate methods to this class
     */
    use TranslateMethods;

    /**
     * Methods to set flash messages
     */
    use FlashMessageMethods;

    /**
     * Sends a redirection header and exits execution.
     *
     * @param array|string $url The url to redirect to.
     *
     * @return self
     */
    public function redirect($url)
    {
        $location = $this->_request->getBasePath();
        $location = str_replace('//', '/', "{$location}/{$url}");
        $this->_response->setStatusCode(302);
        $header = new GenericHeader('Location', $location);
        $this->_response->getHeaders()->addHeader($header);
        return $this->disableRendering();
    }

    /**
     * Disables the view rendering
     *
     * @return self
     */
    public function disableRendering()
    {
        $this->_renderLayout = false;
        $this->_renderView = false;
        return $this;
    }

    /**
     * Returns a previous assigned data value for provided key.
     *
     * @param string $key     The key used to store the data value.
     * @param string $default The default value returned for not found key.
     *
     * @return mixed The previously assigned value for the given key.
     */
    public function get($key, $default = "")
    {
        if (isset($this->_viewVars[$key])) {
            return $this->_viewVars[$key];
        }
        return $default;
    }

    /**
     * Sets a value or an array of values to the data that will be rendered.
     *
     * @param string|array $key   The key used to set the data value. If an
     *  array is given it will iterate through all the elements and set the
     *  values of the array.
     * @param mixed        $value The value to add to set.
     *
     * @return self A self instance for chain method calls.
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $_key => $value) {
                $this->_set($_key, $value);
            }
            return $this;
        }
        $this->_set($key, $value);
        return $this;
    }

    /**
     * Removes the value assigned with provided key.
     *
     * @param string $key $key The key used to set the data value.
     *
     * @return self A self instance for chain method calls.
     */
    public function erase($key)
    {
        unset($this->_viewVars[$key]);
        return $this;
    }

    /**
     * Sets a value to a single key.
     *
     * @param string $key The key used to set the data value.
     * @param mixed $value The value to set.
     *
     * @throws Exception\InvalidArgumentException
     */
    protected function _set($key, $value)
    {
        if (!is_string($key)) {
            throw new Exception\InvalidArgumentException(
                "Key must be a string or a number"
            );
        }
        $this->_viewVars[$key] = $value;
    }
}
