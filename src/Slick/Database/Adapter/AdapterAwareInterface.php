<?php

/**
 * Adapter aware interface
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Adapter;

/**
 * Interface for clients that has database adapter as dependency
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
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