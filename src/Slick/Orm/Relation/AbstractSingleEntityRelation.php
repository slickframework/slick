<?php

/**
 * AbstractSingleEntityRelation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;
use Slick\Common\Inspector\Annotation;
use Slick\Orm\Entity;
use Slick\Orm\Exception;
use Zend\EventManager\Event;

/**
 * AbstractSingleEntityRelation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractSingleEntityRelation extends AbstractRelation
    implements SingleEntityRelationInterface
{

    /**#@+
     * @const string JOIN constant types
     */
    const JOIN_NATURAL             = 'NATURAL';
    const JOIN_NATURAL_LEFT        = 'NATURAL LEFT';
    const JOIN_NATURAL_LEFT_OUTER  = 'NATURAL LEFT OUTER';
    const JOIN_NATURAL_RIGHT       = 'NATURAL RIGHT';
    const JOIN_NATURAL_RIGHT_OUTER = 'NATURAL RIGHT OUTER';
    const JOIN_LEFT_OUTER          = 'LEFT OUTER';
    const JOIN_RIGHT_OUTER         = 'RIGHT OUTER';
    const JOIN_LEFT                = 'LEFT'; // -> The default
    const JOIN_RIGHT               = 'RIGHT';
    const JOIN_INNER               = 'INNER';
    const JOIN_CROSS               = 'CROSS';
    /**#@-*/

    /**
     * @readwrite
     * @var string
     */
    protected $_type = self::JOIN_LEFT;

    /**
     * Sets the join type for SQL statement
     *
     * @param string $type
     *
     * @return AbstractSingleEntityRelation
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * Returns the SQL join type
     *
     * @return string
     */
    public function getType()
    {
        if (is_null($this->_type) || empty($this->_type)) {
            $this->_type = static::JOIN_LEFT;
        }
        return $this->_type;
    }

    /**
     * Creates a relation from notation tag
     *
     * @param Annotation $tag
     * @param Entity $entity
     * @param string $property Property name
     *
     * @throws \Slick\Orm\Exception\UndefinedClassException if the class does
     *  not exists
     * @throws \Slick\Orm\Exception\InvalidArgumentException if the class
     *  does not implement Slick\Orm\EntityInterface interface
     *
     * @return AbstractSingleEntityRelation
     */
    public static function create(Annotation $tag, Entity &$entity, $property)
    {
        $options = ['entity' => $entity];
        $className = null;
        $elfName = get_called_class();

        $className = $tag->getValue();

        $options['foreignKey'] = $tag->getParameter('foreignKey');
        $options['dependent'] = $tag->getParameter('dependent');
        $options['type'] = strtoupper($tag->getParameter('type'));

        if (!class_exists($className)) {
            throw new Exception\UndefinedClassException(
                "The class {$className} is not defined"
            );
        }

        if (!is_subclass_of($className, 'Slick\Orm\EntityInterface')) {
            throw new Exception\InvalidArgumentException(
                "The class {$className} does not implement " .
                "Slick\\Orm\\EntityInterface"
            );
        }

        $options['related'] = $className;
        $options['propertyName'] = $property;

        /** @var AbstractSingleEntityRelation $relation */
        $relation = new $elfName($options);
        $events = $entity->getEventManager();
        $events->attach(
            'beforeSelect',
            function ($event) use ($relation) {
                $relation->updateQuery($event);
            }
        );

        $events->attach(
            'afterSelect',
            function ($event) use ($relation) {
                $relation->hydrate($event);
            }
        );

        return $relation;
    }

    public function hydrate(Event $event)
    {
        $data = $event->getParam('data');

        if ($event->getParam('action') == 'all') {
            foreach ($data as $key => &$row) {
                $entity = $event->getParam('entity')[$key];
                $this->_hydrate($row, $entity);
            }
        } else {
            $entity = $event->getParam('entity');
            $this->_hydrate($data, $entity);
        }

        $event->setParam('data', $data);
    }

    /**
     * @param $data
     * @param $object
     */
    protected function _hydrate(&$data, &$object)
    {

        $columns = $this->getRelated()->getColumns();
        $className = get_class($this->getRelated());
        $options = array();

        /** @var $column Entity\Column */
        foreach ($columns as $column) {
            $prop = $column->name;
            if (isset($data[$prop])) {
                if (is_array($data[$prop])) {
                    $options[$prop] = array_shift($data[$prop]);

                } else {
                    $options[$prop] = $data[$prop];
                }
            }
        }

        $property = $this->getPropertyName();
        $object->$property = new $className($options);
        $object->$property->raw = $data;
    }
}