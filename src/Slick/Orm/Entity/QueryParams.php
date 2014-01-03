<?php

/**
 * QueryParams
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Entity;

/**
 * QueryParams
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class QueryParams extends AbstractQueryParams implements QueryParamsInterface
{
    /**
     * Adds conditions for the query
     *
     * @param array $where The query conditions
     *
     * @return QueryParamsInterface A self instance for method chain calls
     */
    public function where($where)
    {
    	return $this->setWhere($where);
    }

    /**
     * Adds fields list for the query
     *
     * @param array $fields The query fields list
     *
     * @return QueryParamsInterface A self instance for method chain calls
     */
    public function fields($fields)
    {
        return $this->setFields($fields);
    }

    /**
     * Adds order direction for the query
     *
     * @param string $direction The query order direction
     *
     * @return QueryParamsInterface A self instance for method chain calls
     */
    public function direction($direction)
    {
        return $this->setDirection($direction);
    }

    /**
     * Adds order definition for the query
     *
     * @param string $order The query order definition
     *
     * @return QueryParamsInterface A self instance for method chain calls
     */
    public function order($order)
    {
        return $this->setOrder($order);
    }

    /**
     * Adds a paginator to the query params
     *
     * This will be used to determine the limit and page of the query.
     *
     * @param Paginator $paginator The paginator object
     *
     * @return QueryParamsInterface A self instance for method chain calls
     */
    public function setPaginator(Paginator $paginator)
    {
        return $this->setPaginator($paginator);
    }
}