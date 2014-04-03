<?php

/**
 * Text filter
 *
 * @package   Slick\Filter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Filter;

/**
 * Text
 *
 * @package   Slick\Filter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Text extends AbstractFilter implements FilterInterface
{

    /**
     * Returns the result of filtering $value
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
        return filter_var(
            $value,
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_NO_ENCODE_QUOTES
        );
    }
}