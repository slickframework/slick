<?php
/**
 * HasOne
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
use Slick\Orm\Exception;
use Zend\EventManager\Event;

/**
 * HasOne
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasOne extends AbstractSingleEntityRelation
{

    /**
     * @readwrite
     * @var bool HasOne defines related as dependent
     */
    protected $_dependent = true;

    /**
     * Updated provided query with relation joins
     *
     * @param Event $event
     */
    public function updateQuery(Event $event)
    {
        $parentTbl = $this->getEntity()->getTable();
        $relatedTbl = $this->getRelated()->getTable();
        $relPmk = $this->getForeignKey();
        $parentPmk = $this->getEntity()->primaryKey;

        $event->getParam('query')->join(
            $relatedTbl,
            "{$relatedTbl}.{$relPmk} = {$parentTbl}.{$parentPmk}",
            [],
            $this->getType()
        );
    }

    /**
     * Returns foreign key name
     *
     * @return string
     */
    public function getForeignKey()
    {
        if (is_null($this->_foreignKey)) {
            $this->_foreignKey = strtolower($this->_entity->getAlias()) .
                "_id";
        }
        return $this->_foreignKey;
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
        $this->setEntity($entity);
        /** @noinspection PhpUndefinedFieldInspection */
        $prmKey = $entity->primaryKey;
        $related = get_class($this->getRelated());
        $relTable = $this->getRelated()->getTable();
        $frKey = $this->getForeignKey();

        return call_user_func_array(
            array($related, 'first'),
            array(
                [
                    'conditions' => [
                        "{$relTable}.{$frKey} = ?" => $entity->$prmKey
                    ]
                ]
            )
        );
    }
}