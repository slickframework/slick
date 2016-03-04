<?php

/**
 * MVC Controller filter event
 *
 * @package   Slick\Mvc\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Events;

use ArrayAccess;
use Slick\Mvc\Controller;
use Zend\EventManager\Event;
use Slick\Common\BaseMethods;
use Slick\Mvc\Router\RouteInfo;

/**
 * MVC Controller filter event
 *
 * @package   Slick\Mvc\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Controller $controller
 * @property RouteInfo $routeInfo
 */
class Filter extends Event
{

    /**
     * Adds base behavior to this class
     */
    use BaseMethods;

    /**#@+
     * @var string Events triggered by MVC dispatcher
     */
    const BEFORE_FILTER = 'before:filter';
    const AFTER_FILTER  = 'after:filter';
    /**#@-**/

    /**
     * @readwrite
     * @var Controller
     */
    protected $_controller;

    /**
     * @readwrite
     * @var RouteInfo
     */
    protected $_routeInfo;

    /**
     * Sets event based on given options
     *
     * @param array $options
     * @param  string $name Event name
     * @param  string|object $target
     * @param  array|ArrayAccess $params
     */
    public function __construct(
        $options = [], $name = null, $target = null, $params = null)
    {
        parent::__construct($name, $target, $params);
        $this->_createObject($options);
    }
}