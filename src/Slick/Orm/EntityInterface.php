<?php

/**
 * EntityInterface
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm;

use Slick\Orm\Entity\QueryParamsInterface;

/**
 * EntityInterface
 *
 * This interface defines the behavior for a database entity with methods to
 * retrive, edit and remove records from a database table.
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface EntityInterface
{

	/**
     * Retrives the list of entity instances for a given query conditions.
     *
     * @param QueryParamsInterface $params The query parameters to use on
     *  select statement.
     *   
     * @return RecordList A list of records (entity instances).
     */
    public static function all(
    	QueryParamsInterface $params = new NullQueryParams()
    );


}