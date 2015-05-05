<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

use Slick\Common\AnnotationInterface;
use Slick\Common\Exception;
use Slick\Common\Utils\ArrayObject;

/**
 * Class AnnotationList
 *
 * @package Slick\Common\Annotation
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class AnnotationList extends ArrayObject
{

    /**
     * @var int|string
     */
    private $pointer;

    /**
     * Appends an annotation to the current list
     *
     * @param AnnotationInterface $annotation
     * @return AnnotationList
     */
    public function append($annotation)
    {
        $this->offsetSet(null, $annotation);
        return $this;
    }
    /**
     * Sets the value at the specified index to value
     *
     * Override to throw an exception if the value provided is not an
     * instance of annotation interface
     *
     * @param mixed $offset
     * @param AnnotationInterface $value
     *
     * @throws \Slick\Common\Exception\InvalidArgumentException if the value
     * provided is not an instance of annotation interface
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof AnnotationInterface)) {
            throw new Exception\InvalidArgumentException(
                "Only annotation objects can be added to an AnnotationsList."
            );
        }
        parent::offsetSet(null, $value);
    }
    /**
     * Checks if current list contains an annotation with the provided name
     *
     * @param string $name
     *
     * @return boolean True if current list contains an annotation with the
     * provided name, False otherwise
     */
    public function hasAnnotation($name)
    {
        $name = str_replace('@', '', $name);
        foreach ($this as $index => $annotation) {
            /** @var AnnotationInterface $annotation */
            if ($annotation->getName() == $name) {
                $this->pointer = $index;
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the annotation with the provided name
     *
     * @param string $name
     * @return AnnotationInterface
     *
     * @throws \Slick\Common\Exception\InvalidArgumentException if annotation
     *  name is not found in the list
     */
    public function getAnnotation($name)
    {
        if (!$this->hasAnnotation($name)) {
            throw new Exception\InvalidArgumentException(
                "Annotation {$name} is not found in this list."
            );
        }
        return $this[$this->pointer];
    }
}