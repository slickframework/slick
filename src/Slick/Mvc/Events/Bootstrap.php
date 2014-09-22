<?php

/**
 * MVC Bootstrap event
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

/**
 * MVC Bootstrap event
 *
 * @package   Slick\Mvc\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Router $router Application router
 * @property Application $application MVC Application
 *
 * @method Router getRouter() Returns application router
 * @method Application getApplication() Returns application
 */
class Bootstrap extends Event
{

    /**
     * Adds base behavior to this class
     */
    use BaseMethods;

    /**#@+
     * @var string Events triggered by MVC application
     */
    const BEFORE_BOOTSTRAP = 'before:bootstrap';
    const AFTER_BOOTSTRAP  = 'after:bootstrap';
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
     * Sets event based on given options
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->_createObject($options);
    }
}
