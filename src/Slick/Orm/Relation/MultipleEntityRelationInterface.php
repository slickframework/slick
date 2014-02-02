<?php
/**
 * MultipleEntityRelationInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

/**
 *  MultipleEntityRelationInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface MultipleEntityRelationInterface extends RelationInterface
{

    /**
     * Sets the total (limit) rows to retrieve
     *
     * @param int $limit
     *
     * @return MultipleEntityRelationInterface
     */
    public function setLimit($limit);

    /**
     * Returns the current total (limit) rows to retrieve
     *
     * @return int
     */
    public function getLimit();
} 