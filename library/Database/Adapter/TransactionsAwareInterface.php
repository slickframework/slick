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
 * Transactions Aware Interface for database drivers that support transactions
 *
 * @package Slick\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface TransactionsAwareInterface extends AdapterInterface
{

    /**
     * Initiates a transaction a database transaction
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function beginTransaction();

    /**
     * Commits a transaction
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function commit();

    /**
     * Rolls back a transaction
     *
     * @return bool TRUE on success or FALSE on failure.
     *
     * @throws NoActiveTransactionException If no transaction is active.
     */
    public function rollBack();
}