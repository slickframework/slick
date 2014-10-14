<?php

/**
 * MVC Model descriptor
 *
 * @package   Slick\Mvc\Model
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Model;

use Slick\Common\Base;
use Slick\Orm\Annotation\Column;
use Slick\Orm\Entity\Descriptor as SlickOrmDescriptor;
use Slick\Orm\RelationInterface;
use Slick\Utility\Text;

/**
 * MVC Model descriptor
 *
 * @package   Slick\Mvc\Model
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property SlickOrmDescriptor $descriptor Entity descriptor object
 *
 * @method SlickOrmDescriptor getDescriptor() Returns the Entity descriptor
 */
class Descriptor extends Base
{
    /**
     * @readwrite
     * @var string
     */
    protected $_displayField;

    /**
     * @readwrite
     * @var SlickOrmDescriptor
     */
    protected $_descriptor;

    /**
     * @readwrite
     * @var array
     */
    protected $_modelPlural = [];

    /**
     * @readwrite
     * @var array
     */
    protected $_modelSingular = [];

    /**
     * @readwrite
     * @var string
     */
    protected $_primaryKey;

    /**
     * Returns the display field name
     *
     * The display field is used to print out the model instance name
     * when you request to print a model.
     *
     * For example:
     * model as the id, name, address fields, if you print out model with
     * echo $model, it will use the name field to print it or other field
     * if you define $_displayField property.
     *
     * @return string
     */
    public function getDisplayField()
    {
        if (is_null($this->_displayField)) {
            $properties = array_keys($this->getDescriptor()->getColumns());
            foreach ($properties as $property) {
                $name = trim($property, '_');
                $pmk = $this->getDescriptor()->getEntity()->primaryKey;
                if ($name == $pmk) {
                    continue;
                }

                $annotations = $this->getDescriptor()->getInspector()
                    ->getPropertyAnnotations($property);

                if ($annotations->hasAnnotation('@display')) {
                    $this->_displayField = $name;
                    break;
                }
                $this->_displayField = $name;
            }
        }
        return $this->_displayField;

    }

    /**
     * Returns the list of entity columns
     *
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->getDescriptor()->getColumns();
    }

    /**
     * Returns the list of relations of current entity
     *
     * @return RelationInterface[]
     */
    public function getRelations()
    {
        return $this->getDescriptor()->getRelations();
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
        return $this->getDescriptor()->getRelation($name);
    }

    /**
     * Returns the plural form of the class name
     *
     * @param RelationInterface $relation
     *
     * @return string
     */
    public function modelPlural(RelationInterface $relation)
    {
        if (!isset($this->_modelPlural[$relation->getPropertyName()])) {
            $parts = explode('\\', $relation->getRelatedEntity());
            $name = strtolower(end($parts));
            $this->_modelPlural[$relation->getPropertyName()] = Text::plural($name);
        }
        return $this->_modelPlural[$relation->getPropertyName()];
    }

    /**
     * Returns the singular form of the class name
     *
     * @param RelationInterface $relation
     *
     * @return string
     */
    public function modelSingular(RelationInterface $relation)
    {
        if (!isset($this->_modelSingular[$relation->getPropertyName()])) {
            $parts = explode('\\', $relation->getRelatedEntity());
            $this->_modelSingular[$relation->getPropertyName()] = strtolower(end($parts));
        }
        return $this->_modelSingular[$relation->getPropertyName()];
    }

    /**
     * Returns model primary key name
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        if (is_null($this->_primaryKey)) {
            $this->_primaryKey = $this->getDescriptor()
                ->getEntity()->getPrimaryKey();
        }
        return $this->_primaryKey;
    }

    /**
     * Returns the column of a given relation
     *
     * @param RelationInterface $relation
     *
     * @return Descriptor
     */
    public function getRelationDescriptor(RelationInterface $relation)
    {
        return Manager::getInstance()
            ->get(
                new SlickOrmDescriptor(
                    [
                        'entity' => $relation->getRelatedEntity()
                    ]
                )
            );
    }
}
