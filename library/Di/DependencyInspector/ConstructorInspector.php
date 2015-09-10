<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\DependencyInspector;

use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Di\Definition\Object as ObjectDefinition;

/**
 * Constructor Inspector for dependency definition
 *
 * @package Slick\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property ObjectDefinition $definition The definition to work with
 *
 * @method ObjectDefinition getDefinition() Returns current definition
 * @method bool isSatisfiable() Check if construction can be achieved
 */
class ConstructorInspector extends Base
{

    /**
     * @readwrite
     * @var ObjectDefinition
     */
    protected $definition;

    /**
     * @read
     * @var Parameter[]
     */
    protected $arguments;

    /**
     * @read
     * @var bool
     */
    protected $satisfiable = true;

    /**
     * @param ObjectDefinition $definition
     * @return $this|self|ConstructorInspector
     */
    public function setDefinition(ObjectDefinition $definition)
    {
        $this->definition = $definition;
        $this->arguments = null;
        $this->satisfiable = true;
        $this->updateDefinition();
        return $this;
    }

    /**
     * Set definition constructor arguments
     */
    protected function updateDefinition()
    {
        $arguments = [];
        foreach ($this->getArguments() as $param) {
            if ($this->definition->getContainer()->has($param->getId())) {
                $arguments[$param->name] = $this->definition
                    ->getContainer()
                    ->get($param->getId());
                continue;
            }

            if (!$param->isOptional()) {
                $this->satisfiable = false;
                return;
            }

            $arguments[$param->name] = $param->default;
        }
        $this->definition->constructArgs = $arguments;
    }

    /**
     * Gets constructor arguments
     *
     * @return Parameter[]
     */
    protected function getArguments()
    {
        if (is_null($this->arguments)) {
            $methodInspector = new MethodInspector(
                [
                    'name' => '__construct',
                    'definition' => $this->definition
                ]
            );
            $this->arguments = $methodInspector->getArguments();
        }
        return $this->arguments;
    }
}
