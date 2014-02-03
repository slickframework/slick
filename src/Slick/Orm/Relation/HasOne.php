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

use Slick\Database\Query\Sql\Select;
use Slick\Orm\Entity;
use Slick\Orm\Exception;

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
     * @param $action
     * @param Select $query
     * @param array $context
     */
    public function updateQuery($action, Select &$query, array $context = [])
    {
        print_r($action); die ("I am in!!");
        $parentTbl = $this->getEntity()->getTable();
        $relatedTbl = $this->getRelated()->getTable();
        $relPmk = $this->getForeignKey();
        $parentPmk = $this->getEntity()->primaryKey;

        $query->join(
            $this->getRelated()->getTable(),
            "{$relatedTbl}.{$relPmk} = {$parentTbl}.{$parentPmk}",
            [],
            $this->getType()
        );
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