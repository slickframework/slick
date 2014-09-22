<?php

/**
 * MVC Route event
 *
 * @package   Slick\Mvc\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Events;

use Slick\Mvc\Router;
use Slick\Mvc\Application;
use Zend\EventManager\Event;
use Slick\Common\BaseMethods;
use Zend\Http\PhpEnvironment\Request;

/**
 * MVC Route event
 *
 * @package   Slick\Mvc\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Router $router Application router
 * @property Application $application MVC Application
 * @property Request $request HTTP Request
 * @property Router\RouteInfo $routeInfo Routed information
 *
 * @method Router getRouter() Returns application router
 * @method Application getApplication() Returns application
 * @method Request getRequest() Returns the HTTP Request
 * @method Router\RouteInfo getRouteInfo() Returns Routed information
 */
class Route extends Event
{
    /**
     * Adds base behavior to this class
     */
    use BaseMethods;

    /**#@+
     * @var string Events triggered by MVC application
     */
    const BEFORE_ROUTE = 'before:route';
    const AFTER_ROUTE  = 'after:route';
    /**#@-**/

    /**
     * @readwrite
     * @var Router
     */
    protected $_router;

    /**
     * @readwrite
     * @var Application
     */
    protected $_application;

    /**
     * @readwrite
     * @var Request
     */
    protected $_request;

    /**
     * @readwrite
     * @var Router\RouteInfo
     */
    protected $_routeInfo;

    /**
     * Sets event based on given options
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->_createObject($options);
    }
}
