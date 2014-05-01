<?php

/**
 * Container interface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use Slick\Di\Exception\NotFoundException;
use Slick\Di\Exception\DependencyException;
use Slick\Di\Exception\InvalidArgumentException;

/**
 * Dependency container interface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ContainerInterface
{

    /**
     * Returns an entry of the container by its name.
     *
     * @param string $name Entry name or a class name.
     *
     * @throws InvalidArgumentException Name parameter must be of type string.
     * @throws DependencyException Error while resolving the entry.
     * @throws NotFoundException No entry found for the given name.
     *
     * @return mixed
     */
    public function get($name);

    /**
     * Test if the container can provide something for the given name.
     *
     * @param string $name Entry name or a class name.
     *
     * @throws InvalidArgumentException Name parameter must be of type string.
     *
     * @return bool
     */
    public function has($name);
}