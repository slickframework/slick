<?php

/**
 * ContainerAwareInterface
 *
 * @package Slick\Di
 * @author Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @since Version 1.0.0
 */

namespace Slick\Di;

/**
 * ContainerAwareInterface
 *
 * @package Slick\Di
 * @author Filipe Silva <silvam.filipe@gmail.com>
 */
interface ContainerAwareInterface
{

    /**
     * Returns the internal dependency injector container
     *
     * @return ContainerInterface The dependency injector
     */
    public function getContainer();

    /**
     * Sets the dependency injector container
     *
     * @param ContainerInterface $container The injector to set
     *
     * @return Object A self instance for method chain calls
     */
    public function setContainer(ContainerInterface $container);
} 