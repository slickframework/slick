<?php

/**
 * Annotations list
 *
 * @package    Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.1.0
 */

namespace Slick\Common\Inspector;

use ArrayObject;
use Slick\Common\Exception;

/**
 * Annotations list
 *
 * @package    Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class AnnotationsList extends ArrayObject
{

    /**
     * Appends an annotation to the current list
     *
     * @param AnnotationInterface $annotation
     * @return AnnotationsList
     */
    public function append(AnnotationInterface $annotation)
    {
        $this->offsetSet(null, $annotation);
        return $this;
    }

    /**
     * Sets the value at the specified index to value
     *
     * Overrides to throw an exception if the value provided is not an
     * instance of annotation interface
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws \Slick\Common\Exception\InvalidArgumentException if the value
     * provided is not an instance of annotation interface
     */
    public function offsetSet($offset, $value)
    {
        if ($value instanceof AnnotationInterface) {
            parent::offsetSet($value->getName(), $value);
        } else {
            throw new Exception\InvalidArgumentException(
                "Only annotation objects can be added to an AnnotationsList."
            );
        }
    }

    /**
     * Returns the value in the provided offset
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $offset = strtolower($offset);
        return parent::offsetGet($offset);
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
        foreach ($this as $annotation) {
            /** @var AnnotationInterface $annotation */
            if ($annotation->getName() == $name) {
                return true;
            }
        }
        return false;
    }
} 