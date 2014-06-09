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

use Slick\Common\Inspector;
use Slick\Orm\Entity,
    Slick\Common\Base,
    Slick\Utility\ArrayObject,
    Slick\Common\Inspector\AnnotationsList;

/**
 * RelationManager
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property ArrayObject $relations
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
     * @var string[]
     */
    protected $_index = array();

    /**
     * @var array A list of available relations to build
     */
    protected $_classes = array(
        '@hasOne' => '\Slick\Orm\Relation\HasOne',
        '@belongsTo' => '\Slick\Orm\Relation\BelongsTo',
        '@hasMany' => '\Slick\Orm\Relation\HasMany',
        '@hasAndBelongsToMany' => '\Slick\Orm\Relation\HasAndBelongsToMany'
    );

    /**
     * Overrides default constructor to set the relations list object
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->_relations = new ArrayObject();
        parent::__construct($options);


    }

    /**
     * Checks if a property is a relation, creating the relation if it is
     */
    public function check(AnnotationsList $propertyMeta, $property, Entity &$entity)
    {
        foreach ($this->_classes as $tag => $class) {
            if ($propertyMeta->hasAnnotation($tag)) {

                $this->_relations[$property] = call_user_func_array(
                    [$class, 'create'],
                    [
                        $propertyMeta->getAnnotation($tag),
                        $entity,
                        trim($property, '_')
                    ]
                );
                $this->_index[$property] = count($this->_index) + 1;
                $this->_relations[$property]->index = $this->_index[$property];
            }
        }
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

    /**
     * Check if the property with provided name is a relation
     *
     * @param string $propertyName Property name
     *
     * @return bool True if property is in relations list, false otherwise
     */
    public function isARelation($propertyName)
    {
        return array_key_exists($propertyName, $this->_relations);
    }

    /**
     * Adds a class to the list of relations
     *
     * @param $annotation
     * @param $class
     * @throws \Slick\Orm\Exception\InvalidArgumentException
     */
    public static function addClass($annotation, $class)
    {
        if (isset(static::$_classes[$annotation])) {
            throw new InvalidArgumentException(
                "Trying to replace a core relation. User another annotation."
            );
        }
        static::$_classes[$annotation] = $class;
    }

} 