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
use Slick\Di\Exception\NotFoundException;

/**
 * Methods Inspector for dependency definition
 *
 * @package Slick\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MethodsInspector extends Base
{

    /**
     * @readwrite
     * @var ObjectDefinition
     */
    protected $definition;

    /**
     * @read
     * @var array
     */
    protected $methods;

    /**
     * Set the definition that will be inspected
     *
     * @param ObjectDefinition $definition
     * @return $this
     */
    public function setDefinition(ObjectDefinition $definition)
    {
        $this->definition = $definition;
        $this->methods = null;
        $this->updateDefinition();
        return $this;
    }

    /**
     * Updates definition with method calls from inspection
     */
    protected function updateDefinition()
    {
        /**
         * @var string    $name
         * @var Parameter[] $params
         */
        foreach ($this->getMethods() as $name => $params)
        {
            $isInjectable = $this->checkMethodAnnotation($name, '@inject');
            $params = $this->checkParameters($params);
            if (!$params && $isInjectable) {
                throw new NotFoundException(
                    "The method {$name} has annotation '@inject' but ".
                    "current container has no correspondent entry for ".
                    "at least one of its parameters."
                );
            }
            if ($params) {
                $this->definition->setMethod($name, $params);
            }
        }
    }

    /**
     * Check and returns a list of parameters
     *
     * @param Parameter[] $params
     * @return array|bool
     */
    protected function checkParameters(array $params)
    {
        $arguments = [];
        foreach ($params as $param) {
            if ($this->definition->getContainer()->has($param->getId())) {
                $arguments[$param->name] = $this->definition
                    ->getContainer()
                    ->get($param->getId());
                continue;
            }

            if (!$param->isOptional()) {
                return false;
            }

            $arguments[$param->name] = $param->default;
        }
        return $arguments;
    }

    /**
     * Gets a list of methods with their arguments
     *
     * @return array
     */
    public function getMethods()
    {
        if (is_null($this->methods)) {
            $this->methods = [];
            $methods = Inspector::forClass($this->definition->getClassName())
                ->getClassMethods();
            foreach ($methods as $method) {
                $this->checkMethod($method);
            }
        }
        return $this->methods;
    }

    /**
     * Check if a method could is a setter.
     *
     * Setters must begin with 'get' and have one parameter only.
     *
     * @param string $method
     */
    protected function checkMethod($method)
    {
        $isIgnore = $this->checkMethodAnnotation($method, '@ignoreInject');
        $isInject = $this->checkMethodAnnotation($method, '@inject');
        if (
            (!$isIgnore && preg_match('/^set(.*)/i', $method)) ||
            $isInject
        ) {
            $arguments = (new MethodInspector(
                ['definition' => $this->definition, 'name' => $method])
            )
                ->getArguments();
            if (count($arguments) == 1) {
                $this->methods[$method] = $arguments;
            }
        }
    }

    /**
     * Check method has an annotation with provided name
     *
     * @param string $method
     * @param string $annotation
     *
     * @return bool True if annotation is present, false otherwise.
     */
    protected function checkMethodAnnotation($method, $annotation)
    {
        $methodAnnotations = Inspector::forClass(
            $this->definition->getClassName()
        )
            ->getMethodAnnotations($method);
        return $methodAnnotations->hasAnnotation($annotation);
    }
}