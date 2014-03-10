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

use Slick\Common\Base,
    Slick\Session\Session,
    Slick\Session\Driver\Driver;

/**
 * Application flash messages
 *
 * @package   Slick\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FlashMessages extends Base
{

    /**#@+
     * @const string TYPE for message type constants
     */
    const TYPE_ERROR = 0;
    const TYPE_WARNING = 1;
    const TYPE_INFO = 2;
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
        self::TYPE_INFO => 'info',
        self::TYPE_ERROR => 'danger'
    ];

    /**
     * @var FlashMessages Self instance to use with static methods
     */
    protected static $_instance;

    /**
     * Load messages upon creation
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->_messages = $this->getSession()->get('_messages_', []);
    }

    /**
     * Lazy loads session component
     *
     * @return Driver|\Slick\Session\Driver\DriverInterface
     */
    public function getSession()
    {
        if (is_null($this->_session)) {
            $this->_session = Session::get();
        }
        return $this->_session;
    }

    /**
     * Set a flash message of a give type
     *
     * @param int $type
     * @param string $message
     *
     * @return FlashMessages
     */
    public function set($type, $message)
    {
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
        return static::_getInstance()->get();
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
        return static::_getInstance()->set($type, $message);
    }

    /**
     * @return FlashMessages
     */
    protected static function _getInstance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
} 