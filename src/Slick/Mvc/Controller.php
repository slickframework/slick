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

use Slick\Common\Base,
    Slick\Di\DependencyInjector;
use Zend\EventManager\EventManager,
    Zend\EventManager\EventManagerAwareInterface,
    Zend\EventManager\EventManagerInterface,
    Zend\Http\Header\GenericHeader,
    Zend\Http\PhpEnvironment\Request,
    Zend\Http\PhpEnvironment\Response;

/**
 * MVC Controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property bool $renderLayout
 * @property bool $renderView
 * @property Response $response
 * @property Request $request
 * @property string $extension
 * @property EventManager $events
 * @property string $controllerName
 * @property string $actionName
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
     * @readwrite
     * @var EventManager
     */
    protected $_events;

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
     * @readwrite
     * @var string The controller name from the router
     */
    protected $_controllerName;

    /**
     * @readwrite
     * @var string The action name from the router
     */
    protected $_actionName;

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
        $location = str_replace('//', '/', "{$location}/{$url}");
        $this->_response->setStatusCode(302);
        $header = new GenericHeader('Location', $location);
        $this->_response->getHeaders()->addHeader($header);
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
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_class($this),
            )
        );
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

    /**
     * Renders the action and/or template view(s).
     *
     * @throws View\Exception\RenderingErrorException
     * @return null|string
     */
    public function render()
    {
        $results = null;

        $doLayout = $this->renderLayout && $this->getLayout();
        $doView = $this->renderView && $this->getView();

        try {
            if ($doView) {
                $results = $this->getView()
                    ->set($this->_viewVars)
                    ->render();

            }

            if ($doLayout) {
                $results = $this->getLayout()
                    ->set('layoutData', $results)
                    ->set($this->_viewVars)
                    ->render();
            }

            $this->disableRendering();
        } catch (\Exception $exp) {

            throw new View\Exception\RenderingErrorException(
                "Error while rendering view: " . $exp->getMessage()
            );
        }
        return $results;
    }

    /**
     * Set specific view for this request
     *
     * @param string $view
     *
     * @return Controller
     */
    public function setView($view)
    {
        $name = "{$view}.{$this->extension}.twig";
        $this->_view = new View();
        $this->_view->file = $name;
        return $this;
    }

    /**
     * @return \Slick\Mvc\View
     */
    public function getView()
    {
        if (is_null($this->_view)) {
            $name = "{$this->controllerName}/{$this->actionName}";
            $this->setView($name);
        }
        return $this->_view;
    }

    /**
     * Sets the response layout to use
     *
     * @param string $layout
     * @return Controller
     */
    public function setLayout($layout)
    {
        $name = "{$layout}.{$this->extension}.twig";
        $this->_layout = new View();
        $this->_layout->file = $name;
        return $this;
    }

    /**
     * @return \Slick\Mvc\View
     */
    public function getLayout()
    {
        if (is_null($this->_layout)) {
            $this->setLayout('Layouts/default');
        }
        return $this->_layout;
    }


}