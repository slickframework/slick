<?php

/**
 * Entity delete data event
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
 * Entity delete data event
 *
 * @package   Slick\Orm\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property boolean $abort Flag to proceed or abort the save action
 * @property mixed $primaryKey The primary key used in delete action
 */
class Delete extends Event
{
    /**
     * Adds base behavior to this class
     */
    use BaseMethods;

    /**#@+
     * @var string Events triggered by entities
     */
    const BEFORE_DELETE = 'before:delete';
    const AFTER_DELETE  = 'after:delete';
    /**#@-**/

    /**
     * @readwrite
     * @var bool
     */
    protected $_abort = false;

    /**
     * @readwrite
     * @var mixed
     */
    protected $_primaryKey;

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
