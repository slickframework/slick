<?php

/**
 * MVC Dispatch event
 *
 * @package   Slick\Mvc\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Events;

use Slick\Mvc\Application;
use Zend\EventManager\Event;
use Slick\Common\BaseMethods;
use Slick\Mvc\Router\RouteInfo;
use Zend\Http\PhpEnvironment\Response;

/**
 * MVC Dispatch event
 *
 * @package   Slick\Mvc\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property RouteInfo $routeInfo Routed information
 * @property Application $application MVC Application
 * @property Response $response HTTP response
 *
 * @method Application getApplication() Returns application
 * @method RouteInfo getRouteInfo() Returns Routed information
 * @method Response getResponse() Returns HTTP response
 */
class Dispatch extends Event
{

    /**
     * Adds base behavior to this class
     */
    use BaseMethods;

    /**#@+
     * @var string Events triggered by MVC application
     */
    const BEFORE_DISPATCH = 'before:dispatch';
    const AFTER_DISPATCH  = 'after:dispatch';
    /**#@-**/

    /**
     * @readwrite
     * @var Application
     */
    protected $_application;

    /**
     * @readwrite
     * @var RouteInfo
     */
    protected $_routeInfo;

    /**
     * @readwrite
     * @var Response
     */
    protected $_response;

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
