<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition\Resolver;

use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Di\Definition\ObjectDefinitionInterface;

/**
 * Object definition resolver
 *
 * @package Slick\Di\Definition\Resolver
 */
class Object extends Base implements ObjectResolver
{
    /**
     * @readwrite
     * @var ObjectDefinitionInterface
     */
    protected $definition;

    /**
     * Resolves provided definition
     *
     * @return mixed
     */
    public function resolve()
    {
        return $this->setProperties()
            ->setMethods()
            ->definition->getInstance();
    }

    /**
     * Set the definition that this resolver will resolve
     *
     * @param ObjectDefinitionInterface $definition Definition to resolve
     *
     * @return $this|self
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * Call definition methods with parameters
     *
     * @return $this|self
     */
    private function setMethods()
    {
        foreach ($this->definition->getMethods() as $name => $params) {
            call_user_func_array(
                [$this->definition->getInstance(), $name],
                $this->checkValues($params)
            );
        }
        return $this;
    }

    /**
     * Sets property values
     *
     * @return $this|self
     */
    private function setProperties()
    {
        foreach ($this->definition->getProperties() as $name => $value) {
            $this->setProperty($name, $this->checkValue($value));
        }
        return $this;
    }

    /**
     * Set a value to provided property
     *
     * @param string $name  Property name
     * @param mixed  $value Property value
     */
    private function setProperty($name, $value)
    {
        $property = Inspector::forClass($this->definition->getInstance())
            ->getReflection()
            ->getProperty($name);

        if (!$property->isPublic()) {
            $property->setAccessible(true);
        }
        $property->setValue($this->definition->getInstance(), $value);
    }

    private function checkValues(array $params)
    {
        $values = [];
        foreach ($params as $value) {
            $values[] = $this->checkValue($value);
        }
        return $values;
    }

    /**
     * Check if value is a container entry and change it
     *
     * @param mixed $param
     *
     * @return mixed
     */
    private function checkValue($param)
    {
        $value = $param;
        if (
            is_scalar($param) &&
            preg_match('/^@(?P<key>.*)$/i', $param, $result) &&
            $this->definition->getContainer()->has($result['key'])
        ) {
            $value = $this->definition->getContainer()->get($result['key']);
        }
        return $value;
    }
}
