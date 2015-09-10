<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

/**
 * ObjectDefinition is an alias for Di\Definition\Object class.
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ObjectDefinition extends Object
{

    /**
     * Creates a new object definition
     *
     * @param string $className
     *
     * @return \Slick\Di\Definition\Object
     */
    public static function create($className)
    {
        return new Object(['className' => $className]);
    }
}
