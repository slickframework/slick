<?php

/**
 * StaticFilter
 *
 * @package   Slick\Filter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Filter;

/**
 * StaticFilter
 *
 * @package   Slick\Filter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class StaticFilter
{

    /**
     * @var array List of available filter
     */
    public static $filters = [
        'text' => 'Slick\Filter\Text'
    ];

    /**
     * Returns the result of filtering $value
     *
     * @param string $filter Filter class name or alias
     * @param mixed $value
     *
     * @throws Exception\UnknownFilterClassException
     * @throws Exception\CannotFilterValueException If filtering $value
     * is impossible
     *
     * @return mixed
     *
     * @see Slick\Filter\StaticFilter::$filters
     */
    public static function filter($filter, $value)
    {
        if (array_key_exists($filter, static::$filters)) {
            $class = static::$filters[$filter];
        } else if (
        is_subclass_of($filter, 'Slick\Filter\FilterInterface')
        ) {
            $class = $filter;
        } else {
            throw new Exception\UnknownFilterClassException(
                "The validator '{$filter}' is not defined or does not " .
                "implements the Slick\\Filter\\FilterInterface interface"
            );
        }

        /** @var FilterInterface $filter */
        $filter = new $class();
        return $filter->filter($value);
    }
} 