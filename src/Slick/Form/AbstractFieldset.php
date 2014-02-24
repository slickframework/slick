<?php
/**
 * AbstractFieldset
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

use Slick\Utility\WeightList;

/**
 * AbstractFieldset
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property WeightList $elements
 * @property object $object
 */
abstract class AbstractFieldset extends AbstractElement
    implements FieldsetInterface
{
    /**
     * @readwrite
     * @var WeightList
     */
    protected $_elements;

    /**
     * Adds an element to the list
     *
     * @param array|ElementInterface $object
     * @param int $weight
     *
     * @return FieldsetInterface
     */
    public function add($object, $weight = 0)
    {
        if (!$this->elements->isEmpty() && $weight == 0) {
            $weight = $this->elements->last()->weight() + 10;
        }
        // TODO: Add factory for array definition

        $this->elements->insert($object, $weight);
        return $this;
    }

    /**
     * Removes the element with the provided name
     *
     * @param $name Element name to search
     *
     * @return boolean True if the element was found and removed
     *  and false if not found
     */
    public function remove($name)
    {
        $removed = false;
        if ($this->has($name)) {
            $this->elements->rewind();
            while($this->elements->valid()) {
                if ($this->elements->current()->getName() == $name) {
                    $this->elements->remove();
                    $removed = true;
                    break;
                }
                $this->elements->next();
            }
        }

        return $removed;
    }

    /**
     * Returns the element with the provided name
     *
     * @param string $name Element name to search
     *
     * @return ElementInterface
     */
    public function get($name)
    {
        $found = null;
        /** @var ElementInterface $element */
        foreach ($this->elements as $element) {
            if ($element->getName() == $name) {
                $found = $element;
            }
        }
        return $found;
    }

    /**
     * Check if an element with a given name exists
     *
     * @param $name Element name to search
     *
     * @return boolean True if element exists or false if not
     */
    public function has($name)
    {
        $found = false;
        $this->elements->rewind();

        /** @var ElementInterface $element*/
        foreach ($this->elements as $element) {
            if ($element->getName() == $name) {
                $found = true;
                break;
            }
        }
        return $found;
    }

    /**
     * Recursively populate value attributes of elements
     *
     * @param $data
     */
    public function populateValues($data)
    {
        /** @var FieldsetInterface|ElementInterface $element*/
        foreach ($this->elements as $element) {
            if (is_a($element, '\Slick\Form\FieldsetInterface')) {
                $element->populateValues($data);
            } else {
                foreach ($data as $name => $value) {
                    if ($element->getName() == $name) {
                        $element->setValue($value);
                    }
                }

            }
        }
    }

    /**
     * Lazy load of fieldset elements
     *
     * @return WeightList
     */
    public function getElements()
    {
        if (is_null($this->_elements)) {
            $this->_elements = new WeightList();
        }
        return $this->_elements;
    }

    /**
     * Sets fieldset elements
     *
     * @param WeightList $elements
     *
     * @return AbstractFieldset
     */
    public function setElements(WeightList $elements)
    {
        $this->_elements = $elements;
        return $this;
    }

}