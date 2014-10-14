<?php

/**
 * Abstract multiple relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

/**
 * Abstract multiple relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractMultipleRelation extends AbstractRelation
{

    /**
     * @readwrite
     * @var int
     */
    protected $_limit = 100;

    /**
     * @readwrite
     * @var bool
     */
    protected $_singleResult = false;

    /**
     * Returns the limit rows retrieved with this relation
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * Sets the limit rows retrieved with this relation
     *
     * @param int $limit
     *
     * @return self
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }
}
