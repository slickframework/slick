<?php

/**
 * Sql
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database;
use Zend\Validator\Barcode\AdapterInterface;

/**
 * Sql factory class
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Sql
{
    /**
     * @var AdapterInterface
     */
    private $_adapter;

    /**
     * Creates a factory with the provided adapter
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
    }

    public function select()
    {

    }
}