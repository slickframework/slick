<?php

/**
 * Abstract single relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Database\RecordList;
use Slick\Orm\Entity;
use Slick\Orm\Events\Save;
use Slick\Orm\Events\Select;
use Zend\EventManager\SharedEventManager;

/**
 * Abstract single relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property bool $lazyLoad Flag for lazy loading of related record
 *
 * @method AbstractSingleRelation isLazyLoad() Returns lazy load status flag
 */
abstract class AbstractSingleRelation extends AbstractRelation
{

    /**
     * @readwrite
     * @var bool
     */
    protected $_lazyLoad = false;

    /**
     * Sets the entity that defines the relation
     *
     * @param Entity $entity
     *
     * @return self
     */
    public function setEntity(Entity $entity)
    {
        /** @var SharedEventManager $events */
        $events = $this->getContainer()->get('sharedEventManager');
        $events->attach(
            get_class($entity),
            Save::BEFORE_SAVE,
            [$this, 'beforeSave']
        );
        $events->attach(
            get_class($entity),
            Select::BEFORE_SELECT,
            [$this, 'beforeSelect']
        );
        $events->attach(
            get_class($entity),
            Select::AFTER_SELECT,
            [$this, 'afterSelect']
        );
        $this->getContainer()->set('sharedEventManager', $events);
        return parent::setEntity($entity);
    }

    /**
     * Runs before save to set the relation data to be saved
     *
     * @param Save $event
     */
    abstract public function beforeSave(Save $event);

    /**
     * Sets the join information on the select query when lazy load is false
     *
     * @param Select $event
     */
    abstract public function beforeSelect(Select $event);

    /**
     * Fixes the data to be sent to entity creation with related entity object
     *
     * @param Select $event
     */
    public function afterSelect(Select $event)
    {
        if ($this->lazyLoad) {
            return;
        }
        $data = $event->data;
        $multiple = $data instanceof RecordList;
        if ($multiple) {
            $data = $event->data->getArrayCopy();
        } else {
            $data = [$data];
        }
        if (empty($data)) {
            return;
        }
        $related = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity());
        $relatedTable = $related->getEntity()->getTableName();
        $class = $related->getEntity()->getClassName();
        foreach ($data as $key => $row) {

            $pmk =  $this->getEntity()->getPrimaryKey();
            if (isset($row[$pmk]) && is_array($row[$pmk])) {
                $data[$key][$pmk] = reset($row[$pmk]);
            }
            $options = [];
            if (is_array($row)) {
                foreach ($row as $column => $value) {
                    if (preg_match('/' . $relatedTable . '_(.*)/i', $column)) {
                        unset($data[$key][$column]);
                        $name = str_replace($relatedTable . '_', '', $column);
                        $options[$name] = $value;
                    }
                }
            }
            $data[$key][$this->getPropertyName()] = new $class($options);
        }

        if ($multiple) {
            $event->data = $data;
        } else {
            $event->data = reset($data);
        }
    }

}
