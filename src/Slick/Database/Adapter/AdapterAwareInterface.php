<?php

/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 7/25/14
 * Time: 5:48 PM
 */

namespace Slick\Database\Adapter;


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