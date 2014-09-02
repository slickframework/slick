<?php

/**
 * Has many relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Common\Inspector\Annotation;
use Slick\Orm\Annotation\Column;
use Slick\Orm\RelationInterface;
use Slick\Database\RecordList;
use Slick\Orm\Sql\Select;
use Slick\Orm\Entity;

/**
 * Has many relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasMany extends AbstractMultipleRelation implements RelationInterface
{

    /**
     * Tries to guess the foreign key for this relation
     *
     * @return string
     */
    protected function _guessForeignKey()
    {
        $name = $this->getEntity()->getClassName();
        $name = end($name);
        return strtolower($name) .'_id';
    }

    /**
     * Factory method to create a relation based on a column
     * (annotation) object
     *
     * @param Annotation $annotation
     * @param Entity $entity
     * @param string $property
     *
     * @return self
     */
    public static function create(
        Annotation $annotation, Entity $entity, $property)
    {
        /** @var HasMAny $relation */
        $parameters = $annotation->getParameters();
        unset ($parameters['_raw']);
        $relation = new static($parameters);
        $relation->setEntity($entity)->setPropertyName($property);
        $relation->setRelatedEntity($annotation->getValue());
        return $relation;
    }

    /**
     * Lazy loading of relations callback method
     *
     * @return Entity|RecordList
     */
    public function load()
    {
        /** @var Select $sql */
        $sql = call_user_func_array(
            array($this->getRelatedEntity(), 'find'),
            []
        );
        $pmk = $this->getEntity()->getPrimaryKey();
        $sql->where(
            [
                "{$this->getForeignKey()} = :id" => [
                    ':id' => $this->getEntity()->$pmk
                ]
            ]
        );
        $sql->limit($this->getLimit());
        return $sql->all();
    }
}