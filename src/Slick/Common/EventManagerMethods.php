<?php

/**
 * EventManagerMethods
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Common;

use Slick\Di\DependencyInjector;
use Zend\EventManager\EventManager,
    Zend\EventManager\SharedEventManager,
    Zend\EventManager\EventManagerInterface;

/**
 * EventManagerMethods
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
trait EventManagerMethods
{
    /**
     * @var EventManagerInterface
     */
    protected $_events;

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     *
     * @return EventManagerMethods
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_called_class()
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