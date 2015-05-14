<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Fixtures;


use Slick\Database\Adapter\AbstractAdapter;
use Slick\Database\Adapter\TransactionalAdapter;
use Slick\Database\Exception\ServiceException;

/**
 * Custom Adapter used in tests
 *
 * @package Slick\Tests\Database\Fixtures
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CustomAdapter extends TransactionalAdapter
{

    /**
     * Connects to the database service
     *
     * @return AbstractAdapter The current adapter to chain method calls
     *
     * @throws ServiceException If any error occurs while trying to
     *  connect to the database service
     */
    public function connect()
    {
        $this->connected = true;
        return $this;
    }

    /**
     * Returns the schema name for this adapter
     *
     * @return string
     */
    public function getSchemaName()
    {
        return 'test';
    }
}