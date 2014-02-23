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
use Slick\Form\Fieldset\ElementsList;
use Slick\Utility\WeightList;
use Zend\Stdlib\Hydrator\HydratorInterface;
use SplPriorityQueue;

/**
 * AbstractFieldset
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property WeightList $elements
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
     * @param $name Element name to search
     *
     * @return ElementInterface
     */
    public function get($name)
    {
        // TODO: Implement get() method.
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
     * Set the object used by the hydrator
     *
     * @param $object
     *
     * @return FieldsetInterface
     */
    public function setObject($object)
    {
        // TODO: Implement setObject() method.
    }

    /**
     * Returns the object used by hydrator
     *
     * @param $object
     *
     * @return mixed
     */
    public function getObject($object)
    {
        // TODO: Implement getObject() method.
    }

    /**
     * Recursively populate value attributes of elements
     *
     * @param $data
     */
    public function populateValues($data)
    {
        // TODO: Implement populateValues() method.
    }

    /**
     * Set the hydrator to use when binding an object to the element
     *
     * @param  HydratorInterface $hydrator
     * @return FieldsetInterface
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        // TODO: Implement setHydrator() method.
    }

    /**
     * Get the hydrator used when binding an object to the element
     *
     * @return null|HydratorInterface
     */
    public function getHydrator()
    {
        // TODO: Implement getHydrator() method.
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