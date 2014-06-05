<?php

/**
 * Annotation interface
 *
 * @package    Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.1.0
 */

namespace Slick\Common\Inspector;

/**
 * Interface AnnotationInterface
 *
 * @package Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
interface AnnotationInterface
{

    /**
     * Returns the annotations name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the value in the tag
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Returns the value of a given parameter name
     *
     * @param string $name
     * @return mixed
     */
    public function getParameter($name);
} 