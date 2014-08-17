<?php

/**
 * Decimal column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Decimal column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Float extends AbstractColumn
{

    /**
     * @var int
     */
    protected $_digits;

    /**
     * @var int
     */
    protected $_decimal;

    /**
     * Creates a float type column
     *
     * @param string $name
     * @param int $digits
     * @param int $decimal
     */
    public function __construct($name, $digits, $decimal = null)
    {
        parent::__construct($name);
        $this->_decimal = $decimal;
        $this->_digits = $digits;
    }

    /**
     * Returns number of decimal places
     *
     * @return int
     */
    public function getDecimal()
    {
        return $this->_decimal;
    }

    /**
     * Returns number of digits
     *
     * @return int
     */
    public function getDigits()
    {
        return $this->_digits;
    }
}
