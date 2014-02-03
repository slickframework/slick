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
use Slick\Common\Inspector\Tag;
use Slick\Orm\Entity;

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
        return $this->_type;
    }

    /**
     * Creates a relation from notation tag
     *
     * @param Tag $tag
     * @param Entity $entity
     *
     * @return AbstractSingleEntityRelation
     */
    public static function create(Tag $tag, Entity $entity)
    {
        $options = ['entity' => $entity];
        $className = null;
        $elfName = get_called_class();

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

        /** @var AbstractSingleEntityRelation $relation */
        $relation = new $elfName($options);
        $events = $entity->getEventManager();
        print_r($events->getIdentifiers());
        $events->attach(
            'beforeSelect',
            function ($action, &$query, $context) use ($relation) {
                $relation->updateQuery($action, $query, $context);
            }
        );
        return $relation;
    }
}