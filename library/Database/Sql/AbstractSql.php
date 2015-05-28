<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Adapter\TransactionsAwareInterface;

/**
 * Abstract SQL statement
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractSql implements SqlInterface
{

    /**
     * @readwrite
     * @var AdapterInterface|TransactionsAwareInterface The Database adapter
     */
    protected $adapter;

    /**
     * @readwrite
     * @var string The table this query refers to
     */
    protected $table;

    /**
     * Creates the sql with the table name and fields
     *
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->table = $tableName;
    }

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return self
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Return the assigned table
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}