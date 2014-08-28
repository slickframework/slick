<?php

/**
 * Entity save data event
 *
 * @package   Slick\Orm\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Events;

use Zend\EventManager\Event;
use Slick\Common\BaseMethods;

/**
 * Entity save data event
 *
 * @package   Slick\Orm\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property array   $data   The data that will be sent to database
 * @property boolean $abort  Flag to proceed or abort the save action
 * @property string  $action The save action performed
 */
class Save extends Event
{

    /**
     * Adds base behavior to this class
     */
    use BaseMethods;

    /**#@+
     * @var string Events triggered by entities
     */
    const BEFORE_SAVE = 'before:save';
    const AFTER_SAVE  = 'after:save';
    /**#@-**/

    /**#@+
     * @var string Available entity actions that use select
     */
    const INSERT = 'insert';
    const UPDATE = 'update';
    /**#@-**/

    /**
     * @readwrite
     * @var string
     */
    protected $_action = self::INSERT;

    /**
     * @readwrite
     * @var array
     */
    protected $_data = [];

    /**
     * @readwrite
     * @var bool
     */
    protected $_abort = false;

    /**
     * Sets event based on given options
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->_createObject($options);
    }
}
