<?php

/**
 * HasMany
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

use Slick\Common\Inspector\Annotation;
use Slick\Database\RecordList;
use Slick\Orm\Entity;
use Slick\Orm\EntityInterface;

/**
 * HasMany
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasMany extends AbstractMultipleEntityRelation
    implements MultipleEntityRelationInterface
{

    /**
     * @readwrite
     * @var bool BelongsTo defines related as dependent
     */
    protected $_dependent;

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
     * Creates a relation from notation tag
     *
     * @param Annotation $tag
     * @param Entity $entity
     * @param string $property
     *
     * @return HasMany
     */
    public static function create(Annotation $tag, Entity &$entity, $property)
    {
        $options = ['entity' => $entity];
        $className = null;

        $className = $tag->getValue();
        $options['foreignKey'] = $tag->getParameter('foreignKey');
        $options['dependent'] = $tag->getParameter('dependent');
        $options['limit'] = $tag->getParameter('limit');
        $options['related'] = $className;
        $relation = new HasMany($options);
        return $relation;
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
            array($related, 'all'),
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