<?php

/**
 * FieldsetInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * FieldsetInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface FieldsetInterface extends ElementInterface
{

    /**
     * Adds an element to the list
     *
     * @param array|ElementInterface $object
     * @param int $priority
     *
     * @return FieldsetInterface
     */
    public function add($object, $priority = 0);

    /**
     * Removes the element with the provided name
     *
     * @param $name Element name to search
     *
     * @return boolean True if the element was found and removed
     *  and false if not found
     */
    public function remove($name);

    /**
     * Returns the element with the provided name
     *
     * @param $name Element name to search
     *
     * @return ElementInterface
     */
    public function get($name);

    /**
     * Check if an element with a given name exists
     *
     * @param $name Element name to search
     *
     * @return boolean True if element exists or false if not
     */
    public function has($name);

    /**
     * Recursively populate value attributes of elements
     *
     * @param $data
     */
    public function populateValues($data);
} 