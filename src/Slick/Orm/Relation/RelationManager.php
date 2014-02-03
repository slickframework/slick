<?php

/**
 * RelationManager
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

use ArrayObject;
use Slick\Common\Base;
use Slick\Common\Inspector\TagList;
use Slick\Orm\Entity;

/**
 * RelationManager
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RelationManager extends Base
{

    /**
     * @readwrite
     * @var ArrayObject
     */
    protected $_relations;

    /**
     * @readwrite
     * @var Entity
     */
    protected $_entity;

    /**
     * @readwrite
     * @var string[]
     */
    protected $_index = array();

    /**
     * @var array A list of available relations to build
     */
    protected $_classes = array(
        '@HasOne' => '\Slick\Orm\Relation\HasOne',
        '@BelongsTo' => '\Slick\Orm\Relation\BelongsTo',
        '@HasMany' => '\Slick\Orm\Relation\HasMany',
    );

    /**
     * Checks if a property is a relation, creating the relation if it is
     */
    public function check(TagList $propertyMeta, $property)
    {
        foreach ($this->_classes as $tag => $class) {
            $tag = strtolower($tag);
            if ($propertyMeta->hasTag($tag)) {
                $this->_relations[$property] = call_user_func_array(
                    [$class, 'create'],
                    [
                        $propertyMeta->getTag($tag),
                        $this->getEntity()
                    ]
                );
                $this->_index[$property] = count($this->_index) + 1;
            }
        }
    }

    /**
     * Return this manager entity
     *
     * @return Entity
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * Retrieves the relation define on the given property name
     *
     * @param string $propertyName
     *
     * @return RelationInterface|null
     */
    public function getRelation($propertyName)
    {
        $relation = null;
        if (isset($this->_relations[$propertyName])) {
            $relation =  $this->_relations[$propertyName];
        }
        return $relation;
    }

} 