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

use Slick\Common\Base;
use Monolog\Logger,
	Monolog\Handler\SyslogHandler;

/**
 * 
 */
class Log extends Base
{

	/**
     * @read
     * @var array A list of available loggers.
     */
    protected $_loggers = array();

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
     * Gets the logger for the channel with the provided name.
     * 
     * @param string $name The loggers's channel name to retreive.
     * 
     * @return \Monolog\Logger The logger object for the given channel name.
     */
    public static function logger($name = null)
    {
        $log = new Static();
        return $log->getLogger($name);
    }

    /**
     * Gets the logger for the channel with the provided name.
     * 
     * @param string $name The loggers's channel name to retreive.
     * 
     * @return \Monolog\Logger The logger object for the given channel name.
     */
    public function getLogger($name = null)
    {
        $name = is_null($name) ? $this->defaultLogger : $name;
        if (!isset($this->_loggers[$name])) {
            $this->_loggers[$name] = new Logger($name);
            $this->_setDefaultHandlers($this->_loggers[$name]);
        }
        return $this->_loggers[$name];
    }

    /**
     * Adds the default log handlers to the provided logger.
     * 
     * @param MonologLogger $logger The logger object to add the handlers.
     */
    protected function _setDefaultHandlers(\Monolog\Logger $logger)
    {
        if (empty($this->_handlers)) {
        	$socketHandler = new SyslogHandler('myfacility', 'local6');
            array_push($this->_handlers, $socketHandler);
        }

        foreach ($this->_handlers as $handler) {
            $logger->pushHandler($handler);
        }
    }
}