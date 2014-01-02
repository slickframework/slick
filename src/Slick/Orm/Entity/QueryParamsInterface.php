<?php

/**
 * QueryParamsInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Entity;

use Slick\Utility\Paginator;

/**
 * QueryParamsInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface QueryParamsInterface
{

	/**
	 * Adds conditions for the query
	 * 
	 * @param array $where The query conditions
	 * 
	 * @return QueryParamsInterface A self instance for method chain calls
	 */
	public function where($where);

	/**
	 * Adds fields list for the query
	 * 
	 * @param array $where The query fields list
	 * 
	 * @return QueryParamsInterface A self instance for method chain calls
	 */
	public function fields($fields);

	/**
	 * Adds order direction for the query
	 * 
	 * @param array $where The query order direction
	 * 
	 * @return QueryParamsInterface A self instance for method chain calls
	 */
	public function direction($direction);

	/**
	 * Adds order definition for the query
	 * 
	 * @param array $where The query order definition
	 * 
	 * @return QueryParamsInterface A self instance for method chain calls
	 */
	public function order($order);

	/**
	 * Adds a paginator to the query params
	 *
	 * This will be used to determine the limit and page of the query.
	 * 
	 * @param Paginator $paginator The paginator object
	 */
	public function setPaginator(Paginator $paginator);
}