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

use \PDO;
use Slick\Orm\Entity;
use Slick\Orm\Exception;
use Zend\EventManager\Event;

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
     * @param Event $event
     */
    public function updateQuery(Event $event)
    {
        $parentTbl = $this->getEntity()->getTable();
        $relatedTbl = $this->getRelated()->getTable();
        $relPmk = $this->getForeignKey();
        $parentPmk = $this->getEntity()->primaryKey;

        $event->getParam('query')->join(
            $this->getRelated()->getTable(),
            "{$relatedTbl}.{$relPmk} = {$parentTbl}.{$parentPmk}",
            [],
            $this->getType()
        );
    }

    /**
     * Updated provided query with relation joins
     *
     * @param Event $event
     */
    public function hydratate(Event $event)
    {
        $data = $event->getParam('data');
        $columns = $event->getParam('entity')->getColumns();
        $className = get_class($this->getRelated());
        $options = array();
        /** @var $column Entity\Column */
        foreach ($columns as $column) {
            $prop = $column->name;
            if (isset($data[$prop])) {
                if (is_array($data[$prop])) {
                    if (isset($data[$prop][$this->index])) {
                        $options[$prop] = $data[$prop][$this->index];
                    }
                } else {
                    $options[$prop] = $data[$prop];
                }
            }
        }

        $property = $this->getPropertyName();
        $event->getParam('entity')->$property = new $className($options);
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