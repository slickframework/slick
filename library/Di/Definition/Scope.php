<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Common\Utils\Enum;

/**
 * Scope used in the definition
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @method static Scope Singleton()
 * @method static Scope Prototype()
 */
class Scope extends Enum
{

    const SINGLETON = 'singleton';
    const PROTOTYPE = 'prototype';
}
