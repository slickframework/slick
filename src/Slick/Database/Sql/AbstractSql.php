<?php

/**
 * Abstract SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;
use Slick\Database\Adapter\AdapterInterface;


/**
 * Abstract SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractSql implements SqlInterface
{

    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * @var string
     */
    protected $_table;

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return self
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Return the assigned table
     *
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }
}
