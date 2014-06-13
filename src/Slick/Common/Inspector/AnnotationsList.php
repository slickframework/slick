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
use Codeception\Module\Slick;
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
    public function append($annotation)
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
            $offset = $value->getName();
            parent::offsetSet($offset, $value);
        } else {
            throw new Exception\InvalidArgumentException(
                "Only annotation objects can be added to an AnnotationsList."
            );
        }
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

    /**
     * Returns the annotation with the provided name
     *
     * @param string $name
     * @return AnnotationInterface
     *
     * @throws \Slick\Common\Exception\InvalidArgumentException
     */
    public function getAnnotation($name)
    {
        if (!$this->hasAnnotation($name)) {
            throw new Exception\InvalidArgumentException(
                "Annotation {$name} is not found in this list."
            );
        }

        $name = str_replace('@', '', $name);
        return $this[$name];
    }
} 