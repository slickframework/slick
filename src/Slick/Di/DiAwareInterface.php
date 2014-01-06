<?php

/**
 * DiAwareInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

/**
 * DiAwareInterface
 * 
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface DiAwareInterface
{

    /**
     * Returns the internal dependency injector
     * 
     * @return DiInterface The dependency injector
     */
    public function getDi();

    /**
     * Sets the dependency injector
     * 
     * @param DiInterface $dependencyInjector The injector to set
     *
     * @return Object A self instance for method chain calls
     */
    public function setDi(DiInterface $dependencyInjector);
}