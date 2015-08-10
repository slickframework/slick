<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Adapter;

/**
 * Interface for clients that have database adapter as a dependency
 *
 * @package Slick\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface AdapterAwareInterface
{

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return AdapterAwareInterface
     */
    public function setAdapter(AdapterInterface $adapter);

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter();
}
