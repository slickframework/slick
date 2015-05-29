<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Slick\Common\Log\Handler\NullHandler;

/**
 * Factory for a Monolog logger.
 *
 * The default handler is NullHandler
 *
 * @package Slick\Common
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Log extends Base
{

    /**
     * @read
     * @var array A list of available loggers.
     */
    protected static $loggers = array();

    /**
     * @read
     * @var array A stack of handlers
     */
    protected $handlers = array();

    /**
     * @readwrite
     * @var string Sets the name for default logger.
     */
    protected $defaultLogger = 'general';

    /**
     * @readwrite
     * @var string
     */
    protected $prefix = '';

    /**
     * Gets the logger for the channel with the provided name.
     *
     * @param string $name The loggers channel name to retrieve.
     *
     * @return \Monolog\Logger|LoggerInterface The logger object for
     *  the given channel name.
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
        $name = "{$this->prefix}$name";
        if (!isset(static::$loggers[$name])) {
            static::$loggers[$name] = new Logger($name);
            $this->setDefaultHandlers(static::$loggers[$name]);
        }
        return static::$loggers[$name];
    }

    /**
     * Adds the default log handlers to the provided logger.
     *
     * @param Logger $logger The logger object to add the handlers.
     */
    protected function setDefaultHandlers(Logger $logger)
    {
        if (empty($this->handlers)) {
            $socketHandler = new NullHandler();
            array_push($this->handlers, $socketHandler);
        }
        foreach ($this->handlers as $handler) {
            $logger->pushHandler($handler);
        }
    }

}