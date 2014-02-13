<?php

/**
 * SingleEntityRelationInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;
use Zend\EventManager\Event;

/**
 * SingleEntityRelationInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface SingleEntityRelationInterface extends RelationInterface
{

    /**
     * Sets the join type for SQL statement
     *
     * @param string $type
     *
     * @return SingleEntityRelationInterface
     */
    public function setType($type);

    /**
     * Returns the SQL join type
     *
     * @return string
     */
    public function getType();

    /**
     * Updated provided query with relation joins
     *
     * @param \Zend\EventManager\Event $event
     */
    public function updateQuery(Event $event);

    public function hydratate(Event $event);
} 