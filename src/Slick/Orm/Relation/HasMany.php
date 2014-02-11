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

use Slick\Common\Inspector\Tag;
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
    protected $_dependent = true;

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
     * @param Tag $tag
     * @param Entity $entity
     * @param string $property
     *
     * @return HasMany
     */
    public static function create(Tag $tag, Entity &$entity, $property)
    {
        $options = ['entity' => $entity];
        $className = null;

        if (is_string($tag->value)) {
            $className = $tag->value;
        }

        if (is_a($tag->value, 'Slick\Common\Inspector\TagValues')) {
            $className = $tag->value[0];
            $options['foreignKey'] = $tag->value['foreignkey'];
            if ($tag->value->check('dependent')) {
                $options['dependent'] = (boolean) $tag->value['dependent'];
            }

            if ($tag->value->check('limit')) {
                $options['limit'] = $tag->value['limit'];
            }
        }


        $options['related'] = self::_createEntity($className);

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