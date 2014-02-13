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
     * Returns foreign key name
     *
     * @return string
     */
    public function getForeignKey()
    {
        if (is_null($this->_foreignKey)) {
            $this->_foreignKey = strtolower($this->_related->getAlias()) .
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

        return call_user_func_array(
            array($related, 'get'),
            array(
                $entity->raw[$frKey]
            )
        );
    }
}