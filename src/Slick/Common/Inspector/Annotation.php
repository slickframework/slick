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

use Slick\Utility\ArrayMethods;

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
     * @var array
     */
    protected $_commonTags = [
        'author', 'var', 'return', 'throws', 'copyright',
        'license', 'since', 'property', 'method'
    ];

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
            $first = reset($parsedData);
            if ($first === true) {
                $this->_value = key($parsedData);
                array_shift($parsedData);
            }
            $this->_parameters = array_merge($this->_parameters, $parsedData);
        }

        $this->_checkCommonTags();
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

    /**
     * Returns the parameters set in this annotation
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Returns the values as an array
     *
     * @return array
     */
    public function allValues()
    {
        $raw = $this->_parameters['_raw'];
        $values = explode(',', $raw);
        $result = ArrayMethods::trim($values);
        return $result;
    }

    /**
     * Fix the parameters for string tags
     */
    protected function _checkCommonTags()
    {
        if (in_array($this->getName(), $this->_commonTags)) {
            $this->_value = $this->_parameters['_raw'];
        }
    }
}
