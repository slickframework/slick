<?php

/**
 * FilterChain
 *
 * @package   Slick\Filter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Filter;

use Slick\Common\Base;

/**
 * FilterChain
 *
 * @package   Slick\Filter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property FilterInterface[] $filters
 */
class FilterChain extends Base implements FilterInterface
{

    /**
     * @readwrite
     * @var FilterInterface[]
     */
    protected $_filters = [];

    /**
     * Returns the result of filtering $value with all the filters
     * that are attached to this chain.
     *
     * The result will be a nesting of all filters output.
     *
     * @param mixed $value
     *
     * @throws Exception\CannotFilterValueException If filtering $value
     * is impossible
     *
     * @return mixed
     */
    public function filter($value)
    {
        $result = null;
        foreach ($this->_filters as $filter) {
            $result = $filter->filter($value);
            $value = $result;
        }
        return $result;
    }

    /**
     * Add a filter to the chain
     *
     * @param FilterInterface $filter
     *
     * @return FilterChain
     */
    public function add(FilterInterface $filter)
    {
        $this->_filters[] = $filter;
        return $this;
    }
}