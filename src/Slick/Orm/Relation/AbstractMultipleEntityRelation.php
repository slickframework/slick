<?php

/**
 * AbstractMultipleEntityRelation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

/**
 * AbstractMultipleEntityRelation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractMultipleEntityRelation extends AbstractRelation
    implements MultipleEntityRelationInterface
{

    /**
     * @readwrite
     * @var int
     */
    protected $_limit = 25;

    /**
     * Sets the total (limit) rows to retrieve
     *
     * @param int $limit
     *
     * @return AbstractMultipleEntityRelation
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * Returns the current total (limit) rows to retrieve
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }
}