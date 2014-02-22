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
     * @param int $weight
     *
     * @return FieldsetInterface
     */
    public function add($object, $weight = 0);

    public function remove($name);

    public function get($name);

    public function has($name);

    public function setObject($object);

    public function getObject($object);

    public function populateValues($data);
} 