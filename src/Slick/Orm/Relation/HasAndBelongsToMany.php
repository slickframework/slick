<?php

/**
 * Has And Belongs To Many relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Orm\Entity;
use Slick\Database\Sql;
use Slick\Orm\Events\Save;
use Slick\Utility\Text;
use Slick\Orm\Events\Delete;
use Slick\Database\RecordList;
use Slick\Orm\RelationInterface;
use Slick\Database\Sql\Select\Join;
use Zend\EventManager\SharedEventManager;

/**
 * Has And Belongs To Many relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $relatedForeignKey The related table foreign key
 * @property string $relationTable The relation table name
 *
 * @method HasAndBelongsToMany setRelatedForeignKey($foreignKey) Sets the
 * related foreign key for this relation
 * @method HasAndBelongsToMany setRelationTable($relationTbl) Sets the name
 * of the table that holds the many to many relation foreign keys
 */
class HasAndBelongsToMany extends AbstractMultipleRelation implements
    RelationInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $_relatedForeignKey;

    /**
     * @readwrite
     * @var string
     */
    protected $_relationTable;

    /**
     * Returns related foreign key. If its not set tries to infer it from
     * related table name.
     *
     * @return string
     */
    public function getRelatedForeignKey()
    {
        if (is_null($this->_relatedForeignKey)) {
            $tblName = Entity\Manager::getInstance()
                ->get($this->_relatedEntity)
                ->getEntity()->getTableName();
            $name = Text::singular($tblName);
            $this->setRelatedForeignKey("{$name}_id");
        }
        return $this->_relatedForeignKey;
    }

    /**
     * Returns relation table name. If its not set tries to infer it from
     * related table names.
     *
     * @return string
     */
    public function getRelationTable()
    {
        if (is_null($this->_relationTable)) {
            $names = [
                $this->getEntity()->getTableName(),
                Entity\Manager::getInstance()
                    ->get($this->_relatedEntity)
                    ->getEntity()->getTableName()
            ];
            asort($names);
            $this->setRelationTable(implode('_', $names));
        }
        return $this->_relationTable;
    }

    /**
     * Tries to guess the foreign key for this relation
     *
     * @return string
     */
    protected function _guessForeignKey()
    {
        $tblName = $this->getEntity()->getTableName();
        $name = Text::singular($tblName);
        return "{$name}_id";
    }

    /**
     * Lazy loading of relations callback method
     *
     * @param Entity $entity
     *
     * @return RecordList
     */
    public function load(Entity $entity)
    {
        $sql = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity())
            ->getEntity()->find();
        $relEnt = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity())
            ->getEntity();
        $relationTable = $this->getRelationTable();
        $relPrk = $relEnt->getPrimaryKey();
        $relFnk = $this->getRelatedForeignKey();
        $relTblName = $relEnt->getTableName();
        $entFnk = $this->getForeignKey();
        $entPrk = $entity->getPrimaryKey();
        $clause = "{$relationTable}.{$relFnk} = {$relTblName}.{$relPrk}";
        $sql->join($relationTable, $clause, null, null, Join::JOIN_RIGHT)
            ->where(
                [
                    "{$relationTable}.{$entFnk} = :id" => [
                        ':id' => $entity->$entPrk
                    ]
                ]
            )
            ->limit($this->getLimit());

        return $sql->all();
    }

    /**
     * Sets the entity that defines the relation. Sets the triggers
     * for before delete event.
     *
     * @param Entity $entity
     *
     * @return self
     */
    public function setEntity(Entity $entity)
    {
        /** @var SharedEventManager $events */
        $events = $this->getContainer()->get('sharedEventManager');
        $name = $entity->getClassName();
        $events->attach(
            $name,
            Delete::BEFORE_DELETE,
            [$this, 'beforeDelete']
        );
        $events->attach(
            $name,
            Save::AFTER_SAVE,
            [$this, 'afterSave']
        );
        $this->getContainer()->set('sharedEventManager', $events);
        return parent::setEntity($entity);
    }

    /**
     * Deletes all relational data on deleting entity
     *
     * @param Delete $event
     */
    public function beforeDelete(Delete $event)
    {
        $pmkVal = $event->primaryKey;
        $entFrk = $this->getForeignKey();
        Sql::createSql($this->getEntity()->getAdapter())
            ->delete($this->getRelationTable())
            ->where(
                [
                    "{$entFrk} = :id" => [
                        ":id" => $pmkVal
                    ]
                ]
            )
            ->execute();
    }

    /**
     * Handles the after save event
     *
     * @param Save $event
     */
    public function afterSave(Save $event)
    {
        /** @var Entity $entity */
        $entity = $event->getTarget();
        $entity->loadRelations = false;
        $prop = $this->getPropertyName();
        $relPrk = Entity\Manager::getInstance()->get($this->getRelatedEntity())
            ->getEntity()->getPrimaryKey();
        $entPrk = $entity->getPrimaryKey();
        if (isset($entity->$prop) && is_array($entity->$prop)) {
            $this->beforeDelete(new Delete(['primaryKey' => $entity->$entPrk]));
            foreach ($entity->$prop as $object) {
                $relVal = $object;
                if ($object instanceof Entity) {
                    $relVal = $object->$relPrk;
                }
                Sql::createSql($entity->getAdapter())
                    ->insert($this->getRelationTable())
                    ->set(
                        [
                            $this->getForeignKey() => $entity->$entPrk,
                            $this->getRelatedForeignKey() => $relVal
                        ]
                    )
                    ->execute();
            }
        }
        $entity->loadRelations = true;
    }
}
