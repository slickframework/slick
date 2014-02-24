<?php

/**
 * Form
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

use Slick\Di\DependencyInjector;
use Zend\EventManager\EventManagerAwareInterface,
    Zend\EventManager\EventManagerInterface,
    Zend\EventManager\SharedEventManager,
    Zend\EventManager\EventManager;

/**
 * Form
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Form extends AbstractFieldset
    implements FormInterface, EventManagerAwareInterface
{

    /**
     * @readwrite
     * @var array
     */
    protected $_data;

    /**
     * @readwrite
     * @var EventManager
     */
    protected $_events;

    protected $_inputFilter;

    /**
     * Overrides the parent constructor to set name as mandatory param.
     *
     * @param string       $name
     * @param array|object $options
     */
    public function __construct($name, $options = array())
    {
        parent::__construct($options);
        $this->setName($name);
    }

    /**
     * Set data to validate and/or populate elements
     *
     * @param  array $data
     *
     * @return FormInterface
     */
    public function setData($data)
    {
        $this->_data = $data;
        $this->populateValues($data);
        return $this;
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return Form
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_called_class(),
                $this->getName()
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
            $injector = DependencyInjector::getDefault();
            /** @var SharedEventManager $sharedEvent */
            $sharedEvent = $injector->get('DefaultEventManager');
            $events = new EventManager();
            $events->setSharedManager($sharedEvent);
            $this->setEventManager($events);
        }
        return $this->_events;
    }
}