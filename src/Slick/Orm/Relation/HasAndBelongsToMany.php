<?php

/**
 * HasAndBelongsToMany
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

use Slick\Common\Inspector\Tag;
use Slick\Common\Inspector\TagValues;
use Slick\Database\RecordList;
use Slick\Orm\Entity;
use Slick\Orm\EntityInterface;

/**
 * HasAndBelongsToMany
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasAndBelongsToMany extends AbstractMultipleEntityRelation
{

    /**
     * @readwrite
     * @var string The related foreign key name
     */
    protected $_associationFk;

    /**
     * @readwrite
     * @var string
     */
    protected $_joinTable;

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
     * Returns the associations foreign key name
     * @return string
     */
    public function getAssociationFk()
    {
        if (is_null($this->_associationFk)) {
            $this->_associationFk =
                strtolower($this->getRelated()->getAlias()) . "_id";
        }
        return $this->_associationFk;
    }

    /**
     * Returns the join table name for this association
     * @return string
     */
    public function getJoinTable()
    {
        if (is_null($this->_joinTable)) {
            $names = array(
                $this->getRelated()->getTable(),
                $this->getEntity()->getTable()
            );
            asort($names);
            $this->_joinTable = implode('_', $names);
        }
        return $this->_joinTable;
    }

    /**
     * Creates a relation from notation tag
     *
     * @param Tag $tag
     * @param Entity $entity
     * @param $property
     *
     * @return RelationInterface
     */
    public static function create(Tag $tag, Entity &$entity, $property)
    {

        $className = null;
        $options = array();

        if (is_string($tag->value)) {
            $className = $tag->value;
        }

        if (is_a($tag->value, 'Slick\Common\Inspector\TagValues')) {
            $className = $tag->value[0];
            $options['foreignKey'] = self::_getTagValue(
                $tag->value,
                'foreignKey'
            );
            $options['associationFk'] =
                self::_getTagValue($tag->value, 'associationForeignKey');
            $options['joinTable'] = self::_getTagValue(
                $tag->value,
                'joinTable'
            );
            $options['dependent'] = self::_getTagValue(
                $tag->value,
                'dependent',
                true
            );
            $options['limit'] = self::_getTagValue($tag->value, 'limit');
        }

        $options['entity'] = $entity;
        $options['related'] = $className;

        $relation = new HasAndBelongsToMany($options);
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

        $related = $this->getRelated();
        $className = get_class($this->getRelated());
        $relTable = $related->getTable();
        $joiTable = $this->getJoinTable();
        $assFrKey = $this->getAssociationFk();
        $frKey = $this->getForeignKey();
        /** @var Entity $entity */
        $primaryKey = $entity->primaryKey;
        $joinClause = "{$joiTable}.{$assFrKey} = " .
            "{$relTable}.{$related->primaryKey}";
        $rows = $related->query()
            ->select($related->getTable())
            ->join($this->getJoinTable(), $joinClause)
            ->where(
                [
                    "{$joiTable}.{$frKey} = ?" => $entity->$primaryKey
                ]
            )
            ->limit($this->getLimit())
            ->all();

        $result = new RecordList();
        if ($rows && is_a($rows, '\ArrayObject')) {
            foreach ($rows as $row) {
                $result->append(new $className($row));
            }
        }

        return $result;
    }

    /**
     * Gets the tag value
     *
     * @param TagValues $values
     * @param $tag
     * @param bool $bool
     * @param null $default
     * @return bool|null|string
     */
    protected static function _getTagValue(
        TagValues $values, $tag, $bool = false, $default = null)
    {
        $return = $default;
        $tag = strtolower($tag);

        if ($values->check($tag)) {
            if ($bool) {
                $return = (boolean) $values[$tag];
            } else {
                $return = $values[$tag];
            }
        }

        return $return;
    }
}