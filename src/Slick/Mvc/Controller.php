<?php

/**
 * MVC Controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;

use Slick\Common\Base;
use Slick\Di\DependencyInjector;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * MVC Controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property bool $renderLayout
 * @property bool $renderView
 */
abstract class Controller extends Base implements EventManagerAwareInterface
{
    /**
     * @readwrite
     * @var array
     */
    protected $_viewVars;

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
     * @readwrite
     * @var array Request parameters
     */
    protected $_parameters = array();

    /**
     * @readwrite
     * @var string Request extension
     */
    protected $_extension = 'html';

    /**
     * Sets the values to be used in the views.
     *
     * @param string $key The variable name for the view.
     * @param mixed $value The value that will be available in the views
     *  by the key name.
     *
     * @return Controller
     */
    public function set($key, $value = "")
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
     * Sends a redirection header and exits execution.
     *
     * @param array|string $url The url to redirect to.
     */
    public function redirect($url)
    {
        $location = $this->_request->getBasePath();
        $this->_response->setStatusCode(302);
        $this->_response->getHeaders()->addHeader('Location', $location);
        $this->disableRendering();
    }

    /**
     * Disables the view rendering
     */
    public function disableRendering()
    {
        $this->_renderLayout = false;
        $this->_renderView = false;
    }

    /**
     * Sets the values to be used in the views.
     *
     * @param string $key The variable name for the view.
     * @param mixed $value The value that will be available in the views
     *  by the key name.
     *
     * @throws View\Exception\InvalidDataKeyException
     */
    private function _set($key, $value)
    {
        if (!is_string($key)) {
            throw new View\Exception\InvalidDataKeyException(
                "Key must be a string or a number"
            );
        }
        $this->_viewVars[$key] = $value;
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     *
     * @return Application
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(
            __CLASS__,
            get_class($this),
        ));
        $this->_events = $eventManager;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (is_null($this->_events)) {
            $sharedEvents =  DependencyInjector::getDefault()
                ->get('DefaultEventManager');
            $events = new EventManager();
            $events->setSharedManager($sharedEvents);
            $this->setEventManager($events);
        }
        return $this->_events;
    }
}