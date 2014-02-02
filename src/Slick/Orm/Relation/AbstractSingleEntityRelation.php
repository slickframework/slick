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
}