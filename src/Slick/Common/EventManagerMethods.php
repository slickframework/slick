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

use Slick\Di\ContainerBuilder;
use Slick\Di\Definition;
use Zend\EventManager\EventManager,
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
            $container = ContainerBuilder::buildContainer(
                [
                    'DefaultEventManager' => Definition::object(
                        'Zend\EventManager\SharedEventManager'
                    )
                ]
            );
            $sharedEvents = $container->get('DefaultEventManager');
            $events = new EventManager();
            $events->setSharedManager($sharedEvents);
            $this->setEventManager($events);
        }
        return $this->_events;
    }
} 