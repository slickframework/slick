<?php

/**
 * View
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;
use Slick\Common\Base,
    Slick\Template\Template,
    Slick\Mvc\View\Exception;


/**
 * View
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $file
 * @property \Slick\Template\Engine\Twig template
 */
class View extends Base
{
    /**
     * Template file to use
     * @readwrite
     * @var string
     */
    protected $_file;

    /**
     * The template engine that will render the view.
     * @read
     * @var \Slick\Template\EngineInterface
     */
    protected $_template;

    /**
     * The data that will populate the template.
     * @read
     * @var array
     */
    protected $_data = array();

    /**
     * Overrides the constructor to set the template engine.
     *
     * @param array|Object $options The properties for the object
     *  being constructed.
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->_template = new Template(['type' => 'twig']);
    }

    /**
     * Renders this view.
     *
     * @return string The rendered output
     */
    public function render()
    {
        $this->_template->parse($this->file);
        $output = $this->_template->process($this->_data);
        return $output;
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
     *  array is given it will iterate thru all the elements and set the
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
     * @throws View\Exception\InvalidDataKeyException
     */
    protected function _set($key, $value)
    {
        if (!is_numeric($key) && !is_string($key)) {
            throw new Exception\InvalidDataKeyException(
                "Key must be a string or a number"
            );
        }
        $this->_data[$key] = $value;
    }
} 