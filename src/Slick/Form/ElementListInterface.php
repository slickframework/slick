<?php

/**
 * ElementListInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

use Iterator;

/**
 * ElementListInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ElementListInterface extends Iterator
{

    /**
     * Adds an element object to the enf of the list
     * 
     * @param ElementInterface $element ElementInterface object to add
     * 
     * @return ElementListInterface A self instance for method call chains
     */
    public function append(ElementInterface $element);

    /**
     * Check if an element with a give name exists
     * 
     * @param string $name Name of the element to search
     * 
     * @return boolean True if element is found, false otherwise
     */
    public function hasElement($name);

    /**
     * Retrives the element thar has the provided name
     * 
     * @param string $name Name of the element to search
     * 
     * @return ElementInterface The element eith the provided name or boolean
     *  FALSE if element doesn't exists.
     */
    public function getElement($name);

    /**
     * Removes the element with provided name, if element exists
     * 
     * @param string $name Name of the element to search and remove
     * 
     * @return ElementListInterface A self instance for method call chains
     */
    public function removeElement($name);
}