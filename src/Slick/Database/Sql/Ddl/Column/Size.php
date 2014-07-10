<?php

/**
 * Column sizes
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

use MyCLabs\Enum\Enum;

/**
 * Column sizes
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Size extends Enum
{

    /**#@+
     * Column sizes
     */
    const SMALL  = 'small';
    const TINY   = 'tiny';
    const NORMAL = 'normal';
    const MEDIUM = 'medium';
    const LONG   = 'long';
    const BIG    = 'big';
    /**#@-*/

    /**
     * Small column size
     *
     * @return Size
     */
    final public static function small()
    {
        return new static(static::SMALL);
    }

    /**
     * Tiny column size
     *
     * @return Size
     */
    final public static function tiny()
    {
        return new static(static::TINY);
    }

    /**
     * Normal column size
     *
     * @return Size
     */
    final public static function normal()
    {
        return new static(static::NORMAL);
    }

    /**
     * Medium column size
     *
     * @return Size
     */
    final public static function medium()
    {
        return new static(static::MEDIUM);
    }

    /**
     * Long column size
     *
     * @return Size
     */
    final public static function long()
    {
        return new static(static::LONG);
    }

    /**
     * Big column size
     *
     * @return Size
     */
    final public static function big()
    {
        return new static(static::BIG);
    }
}
