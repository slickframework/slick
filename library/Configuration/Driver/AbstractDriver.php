<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Driver;

use Slick\Common\Base;
use Slick\Common\Utils\ArrayMethods;
use Slick\Configuration\ConfigurationInterface;
use Slick\Configuration\Exception;

/**
 * Abstract configuration driver
 *
 * @package Slick\Configuration\Driver
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property array  $data The configuration data array
 * @property string $file The full path to configuration file
 */
abstract class AbstractDriver extends Base implements ConfigurationInterface
{

    /**
     * @readwrite
     * @var mixed Loaded data
     */
    protected $data = null;

    /**
     * @readwrite
     * @var string The full path and name for configuration file.
     */
    protected $file = null;

    /**
     * Gets current configuration data array
     *
     * @return mixed
     */
    public function getData()
    {
        if (is_null($this->data)) {
            $this->load();
        }
        return $this->data;
    }

    /**
     * Loads the data into this configuration driver
     */
    abstract protected function load();

    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key The key used to store the value in configuration
     * @param mixed $default The default value if no value was stored.
     *
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get($key, $default = null)
    {
        return ArrayMethods::getValue($key, $default, $this->getData());
    }

    /**
     * Set/Store the provided value with a given key.
     *
     * @param string $key The key used to store the value in configuration.
     * @param mixed $value The value to store under the provided key.
     *
     * @return $this|self Self instance for method call chains.
     */
    public function set($key, $value)
    {
        $data = $this->getData();
        ArrayMethods::setValue($key, $value, $data);
        $this->data = $data;
        return $this;
    }

    /**
     * Sets the configuration file name
     *
     * @param string $file The configuration file name to set
     *
     * @return $this|self
     *
     * @throws Exception\FileNotFoundException If the file noes not exists.
     */
    public function setFile($file)
    {
        if (!is_null($file) && !is_file($file)) {
            throw new Exception\FileNotFoundException(
                "Configuration file '{$file}' not found."
            );
        }
        $this->file = $file;
        return $this;
    }
}
