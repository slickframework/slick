<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\DefinitionInterface;

/**
 * Value definition class
 *
 * @package Slick\Tests\Di\Definition
 *
 * @property mixed $value The value to store
 *
 * @method $this|Value setValue(mixed $value) Sets the value to store
 */
class Value extends AbstractDefinition implements DefinitionInterface
{

    /**
     * @readwrite
     * @var mixed
     */
    protected $value;

    /**
     * Resolves current definition and returns its value
     *
     * @return mixed
     */
    public function resolve()
    {
        return $this->value;
    }

}