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
 * Factory definition allows creation of object with a callable
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property callable $callable The callable that will create the value
 */
class Factory extends AbstractDefinition implements DefinitionInterface
{

    /**
     * @readwrite
     * @var callable
     */
    protected $callable;

    /**
     * @read
     * @var array
     */
    protected $parameters;

    /**
     * Sets the callable for this factory
     *
     * @param callable $callable
     * @param array $parameters
     *
     * @return $this|self
     */
    public function setCallable(Callable $callable, array $parameters = [])
    {
        $this->callable = $callable;
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Resolves current definition and returns its value
     *
     * @return mixed
     */
    public function resolve()
    {
        return call_user_func_array($this->callable, $this->parameters);
    }
}
