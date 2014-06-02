<?php

/**
 * Scope
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition;

use MyCLabs\Enum\Enum;

/**
 * Definition scope enumerator
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Scope extends Enum
{
    const SINGLETON = 'singleton';
    const PROTOTYPE = 'prototype';

    /**
     * A singleton entry will be computed once and shared.
     * For a class, only a single instance of the class will be created.
     *
     * @return Scope
     */
    public static function SINGLETON()
    {
        return new static(self::SINGLETON);
    }

    /**
     * A prototype entry will be recomputed each time it is asked.
     * For a class, this will create a new instance each time.
     *
     * @return Scope
     */
    public static function PROTOTYPE()
    {
        return new static(self::PROTOTYPE);
    }
} 