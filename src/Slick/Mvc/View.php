<?php

/**
 * MVC View
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use Slick\Common\Base;
use Slick\Template\Template;
use Slick\Template\EngineInterface;

/**
 * MVC View
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $file Template file to use
 * @property array $engineOptions Template engine construct options
 *
 * @property-read EngineInterface $engine  The template engine that
 * will render the view
 * @property-read array $data The data that will populate the template
 *
 * @method array getEngineOptions() Returns current engine construct options
 * @method string getFile() Returns current template file
 * @method array getData() Returns key/value pair array with template data
 * @method string setFile(string $file) Sets template file
 */
class View extends Base
{

    /**
     * @readwrite
     * @var string
     */
    protected $_file;

    /**
     * @read
     * @var EngineInterface
     */
    protected $_engine;

    /**
     * @read
     * @var array
     */
    protected $_data = [];

    /**
     * @readwrite
     * @var array
     */
    protected $_engineOptions = [
        'engine' => 'twig'
    ];

    /**
     * Renders this view.
     *
     * @return string The rendered output
     */
    public function render()
    {
        $this->engine->parse($this->file);
        $output = $this->engine->process($this->data);
        return $output;
    }

    /**
     * Set engine construct options. The engine is reset.
     *
     * @param array $options
     * @return self
     */
    public function setEngineOptions(array $options)
    {
        $this->_engine = null;
        $this->_engineOptions = $options;
        return $this;
    }

    /**
     * Returns the template engine for this view
     *
     * @return EngineInterface
     */
    public function getEngine()
    {
        if (is_null($this->_engine)) {
            $template = new Template($this->engineOptions);
            $this->_engine = $template->initialize();
        }
        return $this->_engine;
    }

    /**
     * Returns a previous assigned data value for provided key.
     *
     * @param string $key     The key used to store the data value.
     * @param string $default The default value returned for not found key.
     *
     * @return mixed The previously assigned value for the given key.
     */
    public function get($key, $default = "")
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return $default;
    }

    /**
     * Sets a value or an array of values to the data that will be rendered.
     *
     * @param string|array $key   The key used to set the data value. If an
     *  array is given it will iterate through all the elements and set the
     *  values of the array.
     * @param mixed        $value The value to add to set.
     *
     * @return View A self instance for chain method calls.
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $_key => $value) {
                $this->_set($_key, $value);
            }
            return $this;
        }
        $this->_set($key, $value);
        return $this;
    }

    /**
     * Removes the value assigned with provided key.
     *
     * @param string $key $key The key used to set the data value.
     *
     * @return View A self instance for chain method calls.
     */
    public function erase($key)
    {
        unset($this->_data[$key]);
        return $this;
    }

    /**
     * Sets a value to a single key.
     *
     * @param string $key The key used to set the data value.
     * @param mixed $value The value to set.
     * @throws Exception\InvalidArgumentException
     */
    protected function _set($key, $value)
    {
        if (!is_string($key)) {
            throw new Exception\InvalidArgumentException(
                "Key must be a string or a number"
            );
        }
        $this->_data[$key] = $value;
    }
}
