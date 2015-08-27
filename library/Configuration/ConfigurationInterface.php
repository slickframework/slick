<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

use Slick\Configuration\Driver\DriverInterface;

/**
 * ConfigurationInterface, defines a configuration driver
 *
 * @package Slick\Configuration
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ConfigurationInterface extends DriverInterface
{

    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key     The key used to store the value in configuration
     * @param mixed  $default The default value if no value was stored.
     *
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get($key, $default = null);

    /**
     * Set/Store the provided value with a given key.
     *
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     *
     * @return $this|self Self instance for method call chains.
     */
    public function set($key, $value);
}