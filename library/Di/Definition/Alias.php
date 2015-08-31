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
 * Alias definition
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $target The entry name this definition points to
 *
 * @method $this|Alias setTarget(string $entryName) Sets the entry name this
 *                                                  definition will point to.
 * @method string getTarget() Gets the entry name that will resolve
 *                            this definition.
 */
class Alias extends AbstractDefinition implements DefinitionInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $target;

    /**
     * Resolves current definition and returns its value
     *
     * @return mixed
     */
    public function resolve()
    {
        return $this->container->get($this->target);
    }
}
