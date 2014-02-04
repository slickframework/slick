<?php

/**
 * BelongsTo
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

use Slick\Orm\Entity;
use Slick\Common\Inspector\Tag;
use Slick\Database\Query\Sql\Select;
use Zend\EventManager\Event;

/**
 * BelongsTo
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BelongsTo extends AbstractSingleEntityRelation
    implements SingleEntityRelationInterface
{

    /**
     * @readwrite
     * @var bool BelongsTo defines related as dependent
     */
    protected $_dependent = true;

    /**
     * Returns foreign key name
     *
     * @return string
     */
    public function getForeignKey()
    {
        if (is_null($this->_foreignKey)) {
            $this->_foreignKey = strtolower($this->_related->getAlias()) .
                "_id";
        }
        return $this->_foreignKey;
    }

    /**
     * Updated provided query with relation joins
     *
     * @param Event $event
     */
    public function updateQuery(Event $event)
    {
        // TODO: Implement updateQuery() method.
    }
}