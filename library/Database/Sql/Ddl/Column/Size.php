<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

use Slick\Common\Utils\Enum;

/**
 * Column sizes
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @method static Size small()  Small column size
 * @method static Size tiny()   Tiny Column size
 * @method static Size normal() Normal column size
 * @method static Size medium() Medium column size
 * @method static Size long()   Long column size
 * @method static Size big()    Big column size
 */
class Size extends Enum
{
    /**#@+
     * @var string Column sizes
     */
    const SMALL  = 'small';
    const TINY   = 'tiny';
    const NORMAL = 'normal';
    const MEDIUM = 'medium';
    const LONG   = 'long';
    const BIG    = 'big';
    /**#@-*/
}