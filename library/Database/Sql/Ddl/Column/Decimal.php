<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Decimal type column
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Decimal extends AbstractColumn
{

    /**
     * @var int
     */
    protected $digits;

    /**
     * @var int
     */
    protected $decimal;

    /**
     * Creates a decimal type column
     *
     * @param string $name
     * @param int $digits
     * @param int $decimal
     */
    public function __construct($name, $digits, $decimal = null)
    {
        parent::__construct(
            $name,
            ['decimal' => $decimal, 'digits' => $digits]
        );
    }

    /**
     * Returns number of decimal places
     *
     * @return int
     */
    public function getDecimal()
    {
        return $this->decimal;
    }

    /**
     * Returns number of digits
     *
     * @return int
     */
    public function getDigits()
    {
        return $this->digits;
    }

}