<?php

/**
 * Application flash messages
 *
 * @package   Slick\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Libs\Session;

use Slick\Common\Base;
use Slick\Di\Container;
use Slick\Di\Definition;
use Slick\Filter\StaticFilter;
use Slick\Di\ContainerBuilder;
use Slick\Session\Driver\Driver;

/**
 * Application flash messages
 *
 * @package   Slick\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @method FlashMessages setContainer(Container $container) Sets the
 * dependency container
 */
class FlashMessages extends Base
{

    /**#@+
     * @const string TYPE for message type constants
     */
    const TYPE_ERROR   = 0;
    const TYPE_WARNING = 1;
    const TYPE_INFO    = 2;
    const TYPE_SUCCESS = 3;
    /**#@-*/

    /**
     * @readwrite
     * @var Driver
     */
    protected $_session;

    /**
     * @write
     * @var array
     */
    protected $_messages = [];

    /**
     * @var array message type descriptions
     */
    public $classes = [
        self::TYPE_SUCCESS => 'success',
        self::TYPE_WARNING => 'warning',
        self::TYPE_INFO    => 'info',
        self::TYPE_ERROR   => 'danger'
    ];

    /**
     * @var FlashMessages Self instance to use with static methods
     */
    protected static $_instance;

    /**
     * @readwrite
     * @var Container
     */
    protected $_container;

    /**
     * Lazy loads session component
     *
     * @return Driver|\Slick\Session\Driver\DriverInterface
     */
    public function getSession()
    {
        if (is_null($this->_session)) {
            $this->_session = $this->getContainer()->get('session');
        }
        return $this->_session;
    }

    /**
     * Set a flash message of a give type
     *
     * @param int $type
     * @param string $message
     *
     * @return self
     */
    public function set($type, $message)
    {
        $type = StaticFilter::filter('number', $type);
        if ($type < 0 || $type > 3) {
            $type = static::TYPE_INFO;
        }
        $this->_messages[$type][] = $message;
        $this->getSession()->set('_messages_', $this->_messages);
        return $this;
    }

    /**
     * Retrieve all messages and flushes them all
     *
     * @return array
     */
    public function get()
    {
        $this->_messages = $this->getSession()->get('_messages_', []);
        $messages = $this->_messages;
        $this->flush();
        return $messages;
    }

    /**
     * clears all messages
     *
     * @return FlashMessages
     */
    public function flush()
    {
        $this->_messages = [];
        $this->getSession()->set('_messages_', $this->_messages);
        return $this;
    }

    /**
     * Retrieve all messages and flushes them all
     *
     * @return array
     */
    public static function getMessages()
    {
        return static::getInstance()->get();
    }

    /**
     * Set a flash message
     *
     * @param int $type
     * @param string $message
     *
     * @return FlashMessages
     */
    public static function setMessage($type, $message)
    {
        return static::getInstance()->set($type, $message);
    }

    /**
     * Returns the internal dependency injector container
     *
     * @return Container The dependency injector
     */
    public function getContainer()
    {
        if (is_null($this->_container)) {
            $def = [
                'session' => Definition::factory(
                    ['Slick\Session\Session', 'get']
                )
            ];
            $this->setContainer(ContainerBuilder::buildContainer($def));
        }
        return $this->_container;
    }

    /**
     * @return FlashMessages
     */
    public static function getInstance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
}
