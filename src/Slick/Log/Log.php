<?php

/**
 * Log
 *
 * @package   Slick\Log
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Log;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Slick\Common\Base;
use Slick\Configuration\Configuration;
use Slick\Log\Handler\NullHandler;
use Slick\Configuration\Exception\FileNotFoundException;

/**
 * Factory for a Monolog logger.
 *
 * The default handler is SysLog
 *
 * @package   Slick\Log
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $defaultLogger The default logger name
 */
class Log extends Base
{

    /**
     * @read
     * @var array A list of available loggers.
     */
    protected static $_loggers = array();

    /**
     * @read
     * @var array A stack of handlers
     */
    protected $_handlers = array();

    /**
     * @readwrite
     * @var string Sets the name for default logger.
     */
    protected $_defaultLogger = 'general';

    /**
     * @readwrite
     * @var string
     */
    protected $_prefix;

    /**
     * Gets the logger for the channel with the provided name.
     *
     * @param string $name The loggers channel name to retrieve.
     *
     * @return \Monolog\Logger|LoggerInterface The logger object for the given channel name.
     */
    public static function logger($name = null)
    {
        $log = new static();
        return $log->getLogger($name);
    }

    /**
     * Gets the logger for the channel with the provided name.
     *
     * @param string $name The loggers channel name to retrieve.
     *
     * @return \Monolog\Logger The logger object for the given channel name.
     */
    public function getLogger($name = null)
    {
        $name = is_null($name) ? $this->defaultLogger : $name;
        $name = "{$this->getPrefix()}$name";
        if (!isset(static::$_loggers[$name])) {
            static::$_loggers[$name] = new Logger($name);
            $this->_setDefaultHandlers(static::$_loggers[$name]);
        }
        return static::$_loggers[$name];
    }

    /**
     * Adds the default log handlers to the provided logger.
     *
     * @param Logger $logger The logger object to add the handlers.
     */
    protected function _setDefaultHandlers(Logger &$logger)
    {
        if (empty($this->_handlers)) {
            $socketHandler = new NullHandler();
            array_push($this->_handlers, $socketHandler);
        }

        foreach ($this->_handlers as $handler) {
            $logger->pushHandler($handler);
        }
    }

    /**
     * Returns the logger prefix to use
     *
     * @return mixed|string
     */
    public function getPrefix()
    {
        if (is_null($this->_prefix)) {
            $hostName = gethostname();
            try {
                $this->_prefix = Configuration::get('config')
                    ->get('logger.prefix', $hostName);
            } catch(FileNotFoundException $exp) {
                $this->_prefix = $hostName;
            }

        }
        return $this->_prefix;
    }
}