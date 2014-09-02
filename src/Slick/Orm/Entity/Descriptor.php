<?php

/**
 * Entity descriptor class
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Entity;

use Slick\Orm\Entity;
use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Orm\Annotation\Column;
use Slick\Orm\RelationInterface;

/**
 * Entity descriptor class
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @method Descriptor setEntity(Entity $entity) Sets current entity
 */
class Descriptor extends Base
{
    /**
     * @read
     * @var Inspector
     */
    protected $_inspector;

    /**
     * @read
     * @var Column[]
     */
    protected $_columns = [];

    /**
     * @var RelationInterface[]
     */
    protected $_relations = [];

    /**
     * @readwrite
     * @var Entity
     */
    protected $_entity;

    /**
     * @var array
     */
    private static $_annotations = [
        'hasMany' => 'Slick\Orm\Relation\HasMany'
    ];

    /**
     * Returns the list of entity columns
     *
     * @return Column[]
     */
    public function getColumns()
    {
        if (empty($this->_columns)) {
            $properties = $this->getInspector()->getClassProperties();
            foreach ($properties as $property) {
                $annotations = $this->getInspector()
                    ->getPropertyAnnotations($property);
                if ($annotations->hasAnnotation('column')) {
                    $this->_columns[$property] =
                        $annotations->getAnnotation('column');
                }
            }
        }
        return $this->_columns;
    }

    /**
     * Returns the list of relations of current entity
     *
     * @return RelationInterface[]
     */
    public function getRelations()
    {
        if (empty($this->_relations)) {
            $properties = $this->getInspector()->getClassProperties();
            foreach ($properties as $property) {
                $annotations = $this->getInspector()
                    ->getPropertyAnnotations($property);
                foreach (static::$_annotations as $name => $class) {
                    if ($annotations->hasAnnotation($name)) {
                        $this->_relations[$property] = call_user_func_array(
                            [$class, 'create'],
                            [
                                $annotations->getAnnotation($name),
                                $this->getEntity(),
                                trim($property, '_')
                            ]
                        );
                        break;
                    }
                }
            }
        }
        return $this->_relations;
    }

    /**
     * Check if a given property is a relation
     *
     * @param string $name
     *
     * @return bool
     */
    public function isRelation($name)
    {
        $relations = $this->getRelations();
        return isset($relations[$name]);
    }

    /**
     * Returns the relation defined in the provided property name, or false
     * if there is no relation defined with that name.
     *
     * @param string $name
     *
     * @return bool|RelationInterface
     */
    public function getRelation($name)
    {
        $relation = false;
        if ($this->isRelation($name)) {
            $relation =  $this->getRelations()[$name];
        }
        return $relation;
    }

    /**
     * Returns the inspector class for current entity
     *
     * @return Inspector
     */
    public function getInspector()
    {
        if (is_null($this->_inspector)) {
            $this->_inspector = new Inspector($this->_entity);
        }
        return $this->_inspector;
    }

    /**
     * Checks if the entity is not just the class name
     *
     * @return Entity
     */
    public function getEntity()
    {
        if (!($this->_entity instanceof Entity)) {
            $class = $this->_entity;
            $this->setEntity(new $class());
        }
        return $this->_entity;
    }
}
