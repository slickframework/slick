<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\DependencyInspector;

use Slick\Common\Annotation\Basic;
use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Di\Definition\ObjectDefinitionInterface;
use Slick\Di\Exception\NotFoundException;

/**
 * Properties Inspector for dependency definition
 *
 * @package Slick\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property ObjectDefinitionInterface $definition
 */
class PropertiesInspector extends Base
{
    /**
     * @readwrite
     * @var ObjectDefinitionInterface
     */
    protected $definition;

    /** @var string[] */
    private $properties;

    /** @var Inspector */
    private $classInspector;

    /**
     * Set the definition that will be updated
     *
     * @param ObjectDefinitionInterface $definition
     * @return $this|self|PropertiesInspector
     */
    public function setDefinition(ObjectDefinitionInterface $definition)
    {
        $this->definition = $definition;
        $this->properties = $this->classInspector = null;
        $this->updateDefinition();
        return $this;
    }

    /**
     * Updated property list on definition based on class inspection
     */
    private function updateDefinition()
    {
        foreach ($this->getProperties() as $property) {
            if ($this->hasInjectAnnotation($property)) {
                $this->addProperty($property);
            }
        }
    }

    /**
     * Gets class property list
     *
     * @return string[]
     */
    private function getProperties()
    {
        if (is_null($this->properties)) {
            $this->properties = $this->getClassInspector()
                ->getClassProperties();
        }
        return $this->properties;
    }

    /**
     * Gets class inspector
     *
     * @return Inspector
     */
    private function getClassInspector()
    {
        if (is_null($this->classInspector)) {
            $this->classInspector = Inspector::forClass(
                $this->definition->getClassName()
            );
        }
        return $this->classInspector;
    }

    /**
     * Check if the property has defined the @inject annotation
     *
     * @param string $property
     *
     * @return bool
     */
    private function hasInjectAnnotation($property)
    {
        $annotations = $this->getClassInspector()
            ->getPropertyAnnotations($property);
        return $annotations->hasAnnotation('@inject');
    }

    /**
     * Get inject annotation for provided property
     *
     * @param string $property
     * @return Basic|\Slick\Common\AnnotationInterface
     */
    private function getInjectAnnotation($property)
    {
        $annotations = $this->getClassInspector()
            ->getPropertyAnnotations($property);
        return $annotations->getAnnotation('@inject');
    }

    /**
     * Get var annotation for provided property
     *
     * @param string $property
     *
     * @return null|string
     */
    private function getVarAnnotationValue($property)
    {
        $value = null;
        $annotations = $this->getClassInspector()
            ->getPropertyAnnotations($property);
        if ($annotations->hasAnnotation('@var')) {
            $value = trim(
                $annotations->getAnnotation('@var')->getValue(),
                '\\'
            );
        }
        return $value;
    }

    /**
     * Adds property to the definition property list
     *
     * @param string $property
     */
    private function addProperty($property)
    {
        $id = $this->getVarAnnotationValue($property);
        $value = $this->getInjectAnnotation($property)->getValue();
        if (is_string($value)) {
            $id = trim($value, '\\');
        }
        $container = $this->definition->getContainer();

        if (!$container->has($id)) {
            throw new NotFoundException(
                "The property {$property} has annotation '@inject' but ".
                "current container has no correspondent entry for ".
                "the type or key entered."
            );
        }

        $this->definition->setProperty($property, $container->get($id));
    }
}
