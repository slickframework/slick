<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Adapter;

use Slick\Database\Exception\NoActiveTransactionException;

/**
 * A abstract implementation for adapters that support transactions
 *
 * @package Slick\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class TransactionalAdapter extends AbstractAdapter
{


    /**
     * Initiates a transaction a database transaction
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function beginTransaction()
    {
        $this->checkConnection();
        return $this->handler->beginTransaction();
    }

    /**
     * Commits a transaction
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function commit()
    {
        $this->checkConnection();
        return $this->handler->commit();
    }

    /**
     * Rolls back a transaction
     *
     * @return bool TRUE on success or FALSE on failure.
     *
     * @throws NoActiveTransactionException If no transaction is active.
     */
    public function rollBack()
    {
        $this->checkConnection();
        return $this->handler->rollBack();
    }
}