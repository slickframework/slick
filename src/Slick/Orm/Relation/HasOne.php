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

use Slick\Common\Inspector\Tag;
use Slick\Database\Query\Sql\Select;
use Slick\Orm\Entity;
use Slick\Orm\Exception;

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
     * @param Select $query
     */
    public function updateQuery(Select &$query)
    {
        $parentTbl = $this->getEntity()->getTable();
        $relatedTbl = $this->getRelated()->getTable();
        $relPmk = $this->getForeignKey();
        $parentPmk = $this->getEntity()->primaryKey;

        $query->join(
            $this->getRelated()->getTable(),
            "{$relatedTbl}.{$relPmk} = {$parentTbl}.{$parentPmk}",
            [],
            $this->getType()
        );
    }

    /**
     * Creates a relation from notation tag
     *
     * @param Tag    $tag
     * @param Entity $entity
     *
     * @return HasOne
     */
    public static function create(Tag $tag, Entity $entity)
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
            if ($tag->value->check('type')) {
                $options['type'] = strtoupper($tag->value['type']);
            }
        }

        $options['related'] = self::_createEntity($className);

        $hasOne = new HasOne($options);
        return $hasOne;
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
}