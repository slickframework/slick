<?php

/**
 * MVC Application event
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;

use Zend\EventManager\Event;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * MVC Application event
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MvcEvent extends Event
{

    /**#@+
     * Mvc events triggered by event manager
     */
    const EVENT_BOOTSTRAP = 'bootstrap';
    const EVENT_DISPATCH = 'dispatch';
    const EVENT_DISPATCH_ERROR = 'dispatch.error';
    const EVENT_ROUTE = 'route';
    /**#@-*/

    /**
     * @readwrite
     * @var Router Application router
     */
    protected $_router;

    /**
     * @readwrite
     *
     * @var Response
     */
    protected $_response;

    /**
     * @readwrite
     * @var Request
     */
    protected $_request;

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    /**
     * @return \Zend\Http\PhpEnvironment\Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Response $response
     */
    public function setResponse($response)
    {
        $this->_response = $response;
    }

    /**
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @param \Slick\Mvc\Router $router
     */
    public function setRouter($router)
    {
        $this->_router = $router;
    }

    /**
     * @return \Slick\Mvc\Router
     */
    public function getRouter()
    {
        return $this->_router;
    }


} 