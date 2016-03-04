<?php

/**
 * Entity data selection event
 *
 * @package   Slick\Orm\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Events;

use Slick\Common\BaseMethods;
use Slick\Orm\Entity;
use Zend\EventManager\Event;
use Slick\Database\RecordList;

/**
 * Entity data selection event
 *
 * @package   Slick\Orm\Events
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property array $params The list os params used when calling the method
 * @property string $action The method called that fires select events
 * @property Entity $entity The entity doing the select
 * @property \Slick\Database\Sql\Select $sqlQuery The select query used
 * @property array|RecordList $data The resulting data
 * @property boolean $singleItem Flag for result data that is true when
 * the data property represents a single record (entity).
 *
 * @method boolean isSingleItem() is true when
 * the data property represents a single record (entity).
 *
 */
class Select extends Event
{

    /**#@+
     * @var string Events triggered by entities
     */
    const BEFORE_SELECT = 'before:select';
    const AFTER_SELECT  = 'after:select';
    const BEFORE_COUNT  = 'before:count';
    /**#@-**/

    /**#@+
     * @var string Available entity actions that use select
     */
    const GET        = 'get';
    const FIND_ALL   = 'find:all';
    const FIND_FIRST = 'find:first';
    const FIND_COUNT = 'find:count';
    /**#@-**/

    /**
     * Adds base behavior to this class
     */
    use BaseMethods;

    /**
     * @readwrite
     * @var Entity
     */
    protected $_entity;

    /**
     * @readwrite
     * @var boolean
     */
    protected $_singleItem;

    /**
     * @readwrite
     * @var \Slick\Database\Sql\Select
     */
    protected $_sqlQuery;

    /**
     * @readwrite
     * @var array
     */
    protected $_params = [];

    /**
     * @readwrite
     * @var string
     */
    protected $_action;

    /**
     * @readwrite
     * @var array|RecordList
     */
    protected $_data;

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
