<?php

/**
 * Events
 * 
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Common;

/**
 * Events handle event creation and triggering.
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class Events
{
    /**
     * @var array A list of callback methods.
     */
    protected static $_callbacks = array();

    /**
     * Avoid the creation of an ArrayMethods instance.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        // do nothing
    }

    /**
     * Avoid the clonation of an ArrayMethods instance.
     * @codeCoverageIgnore
     */
    private function __clone()
    {
        // do nothing
    }
    
    /**
     * Adds a callback for an event type.
     * 
     * @param string   $type     The event type where to add the callback.
     * @param callable $callback The callback to runn on event triggering.
     */
    public static function add($type, $callback)
    {
        if (empty(Events::$_callbacks[$type])) {
            Events::$_callbacks[$type] = array();
        }
        Events::$_callbacks[$type][] = $callback;
    }
    
    /**
     * Fires all the callbacks for a given event type.
     * 
     * @param string $type       The event type that will be triggered.
     * @param mixed  $parameters Parameters to pass to the callbacks.
     */
    public static function fire($type, $parameters = array())
    {
        if (!empty(Events::$_callbacks[$type])) {
            foreach (Events::$_callbacks[$type] as $callback) {
                call_user_func_array($callback, $parameters);
            }
        }
    }
    
    /**
     * Removes a callback for a given event type.
     * 
     * @param string $type     The event type where to remove the callback.
     * @param string $callback The callbacl to remove.
     */
    public static function remove($type, $callback)
    {
        if (!empty(Events::$_callbacks[$type])) {
            foreach (Events::$_callbacks[$type] as $i => $found) {
                if ($callback == $found) {
                    unset(Events::$_callbacks[$type][$i]);
                }
            }
        }
    }   
}
