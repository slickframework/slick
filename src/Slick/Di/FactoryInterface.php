<?php

/**
 * Factory Interface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use Slick\Di\Exception\NotFoundException,
    Slick\Di\Exception\DependencyException,
    Slick\Di\Exception\InvalidArgumentException;

/**
 * Defines an entity factory for dependency container
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface FactoryInterface
{

    /**
     * Resolves an entry by its name. If given a class name, it will return
     * a new instance of that class.
     *
     * @param string $name       Entry name or a class name.
     * @param array  $parameters Optional parameters to use to build the entry.
     *                           Use this to force specific parameters to
     *                           specific values. Parameters not defined in
     *                           this array will be automatically resolved.
     *
     * @throws InvalidArgumentException Name parameter must be of type string.
     * @throws DependencyException      Error while resolving the entry.
     * @throws NotFoundException        Entry or class not found for
     *  the given name.
     *
     * @return mixed
     */
    public function make($name, array $parameters = []);
} 