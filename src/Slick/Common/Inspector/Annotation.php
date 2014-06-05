<?php

/**
 * Annotation
 *
 * @package    Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.1.0
 */

namespace Slick\Common\Inspector;

/**
 * Annotation
 *
 * @package    Slick\Common\Inspector
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class Annotation implements AnnotationInterface
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var mixed
     */
    protected $_value = false;

    /**
     * @var array
     */
    protected $_parameters = [];

    /**
     * Creates an annotation with parsed data
     *
     * @param string $name
     * @param mixed $parsedData
     */
    public function __construct($name, $parsedData)
    {
        $this->_name = $name;
        $this->_value = $parsedData;
        if (is_array($parsedData)) {
            $this->_parameters = array_merge($this->_parameters, $parsedData);
        }
    }

    /**
     * Returns the annotations name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;

    }

    /**
     * Returns the value in the provided index
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Returns the value of a given parameter name
     *
     * @param string $name
     * @return mixed
     */
    public function getParameter($name)
    {
        if (isset($this->_parameters[$name])) {
            return $this->_parameters[$name];
        }
        return null;
    }
}