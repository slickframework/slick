<?php

/**
 * Varchar column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Varchar column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Varchar extends AbstractColumn
{

    /**
     * @var int
     */
    protected $_length;

    /**
     * Sets name and length
     *
     * @param string  $name
     * @param integer $length
     */
    public function __construct($name, $length)
    {
        $this->_name = $name;
        $this->_length = $length;
    }

    /**
     * Gets column length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->_length;
    }
}
