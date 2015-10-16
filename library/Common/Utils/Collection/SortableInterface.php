<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils\Collection;

/**
 * Sortable Interface
 *
 * @package Slick\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface SortableInterface
{
    /**
     * Sorts current collection with provided callable
     *
     * @param callable $callable
     * @return $this|self|SortableInterface
     */
    public function sortWith(callable $callable);
}
