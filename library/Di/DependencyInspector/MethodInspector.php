<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\DependencyInspector;

use ReflectionParameter;
use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Di\Definition\Object as ObjectDefinition;

/**
 * Method Inspector for dependency definition
 *
 * @package Slick\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string      $name Method name
 * @property
 * @property Parameter[] $arguments Method argument list
 */
class MethodInspector extends Base
{

    /**
     * @readwrite
     * @var ObjectDefinition
     */
    protected $definition;

    /**
     * @readwrite
     * @var string
     */
    protected $name;

    /**
     * @var ReflectionParameter[]
     */
    protected $metaData;

    /**
     * @read
     * @var Parameter[]
     */
    protected $arguments;

    /**
     * Set object definition
     *
     * @param ObjectDefinition $definition
     * @return $this|self|MethodInspector
     */
    public function setDefinition(ObjectDefinition $definition)
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * Gets constructor reflection object
     *
     * @return ReflectionParameter[]
     */
    protected function getMetaData()
    {
        if (is_null($this->metaData)) {
            $this->metaData = [];
            $inspector = Inspector::forClass($this->definition->className);
            if ($inspector->hasMethod($this->name)) {
                $this->metaData = $inspector
                    ->getReflection()
                    ->getMethod($this->name)
                    ->getParameters();
            }
        }
        return $this->metaData;
    }

    /**
     * Gets method arguments
     *
     * @return Parameter[]
     */
    public function getArguments()
    {
        if (is_null($this->arguments)) {
            $this->arguments = [];
            $parameters = $this->getMetaData();
            foreach ($parameters as $param) {
                $this->arguments[] = $this->getParameter($param);
            }
        }
        return $this->arguments;
    }

    /**
     * Creates a Parameter object from provided ReflectionParameter object
     *
     * @param \ReflectionParameter $param
     *
     * @return Parameter
     */
    private function getParameter(\ReflectionParameter $param)
    {
        $defaultValue = null;
        if ($isOptional = $param->isOptional()) {
            $defaultValue = $param->getDefaultValue();
        }
        return new Parameter(
            [
                'name' => $param->getName(),
                'id' => $param->getClass(),
                'default' => $defaultValue,
                'optional' => $isOptional
            ]
        );
    }
}