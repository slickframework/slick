<?php

/**
 * BelongsTo
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

use Slick\Common\Inspector\Tag;
use Slick\Database\RecordList;
use Slick\Orm\Entity;
use Slick\Orm\EntityInterface;
use Zend\EventManager\Event;

/**
 * BelongsTo
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BelongsTo extends AbstractSingleEntityRelation
    implements SingleEntityRelationInterface
{

    /**
     * @readwrite
     * @var bool BelongsTo defines related as dependent
     */
    protected $_dependent = true;

    /**
     * Creates a relation from notation tag
     *
     * @param Tag $tag
     * @param Entity $entity
     * @param string $property Property name
     *
     * @throws \Slick\Orm\Exception\UndefinedClassException if the class does
     *  not exists
     * @throws \Slick\Orm\Exception\InvalidArgumentException if the class
     *  does not implement Slick\Orm\EntityInterface interface
     *
     * @return BelongsTo
     */
    public static function create(Tag $tag, Entity &$entity, $property)
    {
        /** @var BelongsTo $relation */
        $relation = parent::create($tag, $entity, $property);
        $entity->getEventManager()->attach(
            'prepareForInsert',
            function ($event) use ($relation) {
                $relation->prepareInsertUpdate($event);
            }
        );

        $entity->getEventManager()->attach(
            'prepareForUpdate',
            function ($event) use ($relation){
                $relation->prepareInsertUpdate($event);
            }
        );
        return $relation;
    }

    /**
     * Returns foreign key name
     *
     * @return string
     */
    public function getForeignKey()
    {
        if (is_null($this->_foreignKey)) {
            $this->_foreignKey = strtolower($this->getRelated()->getAlias()) .
                "_id";
        }
        return $this->_foreignKey;
    }

    /**
     * Updated provided query with relation joins
     *
     * @param Event $event
     */
    public function updateQuery(Event $event)
    {
        $parentTbl = $this->getEntity()->getTable();
        $relatedTbl = $this->getRelated()->getTable();
        $relPrimary = $this->getRelated()->primaryKey;
        $frKey = $this->getForeignKey();

        $event->getParam('query')->join(
            $relatedTbl,
            "{$parentTbl}.{$frKey} = {$relatedTbl}.{$relPrimary}",
            [],
            $this->getType()
        );

    }

    /**
     * Lazy loading of relations callback method
     *
     * @param EntityInterface $entity
     *
     * @return Entity|RecordList
     */
    public function load(EntityInterface $entity)
    {
        /** @var Entity $entity */
        $this->setEntity($entity);
        $related = get_class($this->getRelated());
        $frKey = $this->getForeignKey();
        $data = null;

        if (
            isset($entity->raw[$frKey]) &&
            is_callable(array($related, 'get'))
        ) {
            $data = call_user_func_array(
                array($related, 'get'),
                array(
                    $entity->raw[$frKey]
                )
            );
        }

        return $data;
    }

    public function prepareInsertUpdate(Event $event)
    {
        $data = $event->getParam('data');
        $raw = $event->getParam('raw');
        $value = $this->_needPrepare($data, $raw);
        if ($value) {
            $data[$this->getForeignKey()] = $value;
        }
        $event->setParam('data', $data);
    }

    protected function _needPrepare($data, $raw)
    {
        $search = [$this->getPropertyName(), $this->getForeignKey()];

        foreach ($search as $value) {
            if (in_array($value, array_keys($data))) {
                return $this->_verifyValue($data[$value]);
            }

            if (in_array($value, array_keys($raw))) {
                return $this->_verifyValue($raw[$value]);
            }
        }
        return false;
    }

    protected function _verifyValue($object)
    {
        $prk = $this->getRelated()->primaryKey;
        $value = false;

        if (is_a($object, 'Slick\Orm\EntityInterface')) {
            $value = intval($object->$prk);
        }

        if (is_array($object)) {
            $object = (object) $object;
        }

        if (is_object($object)) {
            $value = intval($object->$prk);
        }

        if (is_string($object) || is_integer($object)) {
            $value = intval($object);
        }

        return $value;
    }
}