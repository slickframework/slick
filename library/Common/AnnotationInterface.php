<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;

/**
 * Annotation Interface used to create custom annotation classes
 *
 * @package Slick\Common
 */
interface AnnotationInterface
{
    /**
     * Returns the annotation name
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

    /**
     * Annotation factory handler for property assignment
     *
     * @param string       $alias      The alias used in the comment as a tag
     * @param array|mixed  $parameters The Metadata from the tag
     *
     * @return AnnotationInterface
     */
    public function process($alias, $parameters);
}
