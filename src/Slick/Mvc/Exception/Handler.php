<?php

/**
 * Exception handler
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Exception;

use Slick\Common\Base;
use Exception,
    ErrorException;

/**
 * Exception handler (Handles uncaught exceptions)
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Handler extends Base
{

    /**
     * @var array A key value pair of exception types and its handlers
     */
    protected static $_handlers = [
        'Exception' => 'Slick\Mvc\Exception\Handlers\DefaultHandler'
    ];

    /**
     * Adds a handler to the stack of exception handlers
     *
     * @param string $type    Exception type (interface or class name)
     * @param string $handler HandlerInterface class name
     */
    public static function add($type, $handler)
    {
        static::$_handlers[$type] = $handler;
    }

    /**
     * Handles the exception
     *
     * @param Exception $exp
     */
    public static function handle(Exception $exp)
    {
        $className = static::getHandler($exp);
        /** @var HandlerInterface $handler */
        $handler = new $className(['exception' => $exp]);
        $handler->getResponse()->send();
    }

    /**
     * Handles the PHP errors
     *
     * @param int $errorNumber Level of the error raised
     * @param string $message Error message
     * @param string|null $file Filename that the error was raised in
     * @param int $line Line number the error was raised at
     * @param array $context Array of every variable that existed
     *  in the scope the error was triggered in
     *
     * @throws \ErrorException
     *
     * @return boolean
     */
    public static function handleError(
        $errorNumber,  $message, $file = null, $line = 0,  $context = [])
    {
        throw new ErrorException($message, 0, $errorNumber, $file, $line);
    }

    /**
     * Gets the defined handler class name for provided exception
     *
     * @param Exception $exp
     *
     * @return string Handler class name
     */
    protected static function getHandler(Exception $exp)
    {
        $handler = static::$_handlers['Exception'];
        $myList = array_reverse(static::$_handlers);
        foreach ($myList as $type => $className) {
            if (is_a($exp, $type)) {
                $handler = $className;
                break;
            }
        }
        return $handler;
    }

} 