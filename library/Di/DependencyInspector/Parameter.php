<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\DependencyInspector;

use ReflectionClass;
use Slick\Common\Base;

/**
 * Parameter definition structure
 *
 * @package Slick\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string|null $id      The parameter type hint class name
 * @property string      $name    Parameter name
 * @property bool        $options Parameter optional flag
 * @property mixed       $default Default value
 *
 * @method bool isOptional() Check whenever parameter is optional
 */
class Parameter extends Base
{

    /**
     * @readwrite
     * @var string|null
     */
    protected $id;

    /**
     * @readwrite
     * @var string
     */
    protected $name;

    /**
     * @readwrite
     * @var bool
     */
    protected $optional;

    /**
     * @readwrite
     * @var mixed
     */
    protected $default = null;

    /**
     * Gets
     * @return null|string
     */
    public function getId()
    {
        if ($this->id instanceof ReflectionClass) {
            $this->id = $this->id->getName();
        }
        return $this->id;
    }
}
