<?php

/**
 * FieldsetListInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

/**
 * FieldsetListInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface FieldsetListInterface extends ElementListInterface
{
	/**
     * Adds an fieldset object to the enf of the list
     * 
     * @param FieldsetInterface $fieldset FieldsetInterface object to add
     * 
     * @return FieldsetListInterface A self instance for method call chains
     */
    public function append(FieldsetInterface $fieldset);
}