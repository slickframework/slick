<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;


/**
 * Accepts a string representation of a value and retrieves the real value
 *
 * @package Slick\Common\Annotation
 */
class ParameterValue
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var mixed
     */
    private $realValue;

    /**
     * Creates an instance with raw value
     *
     * @param string $value The string that will be analysed
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Analyse the raw value and converts/parses to its real value
     *
     * @return array|bool|float|int|mixed|string|null All possibilities of values
     */
    public function getRealValue()
    {
        if (is_null($this->realValue)) {
            $this->realValue = $this->analise($this->value);
        }
        return $this->realValue;
    }

    /**
     * Analyses the current raw string to retrieve its real value
     *
     * @param string $value The string to be analysed
     *
     * @return mixed
     */
    private function analise($value)
    {
        return $this->checkArrayValue($value);
    }

    /**
     * Analyse value to parse simple arrays
     *
     * This method retrieves simple arrays or list of values. Example:
     * "[one, 2, false]" will become [0=>'one', 1=>2, 2=>false]
     *
     * It is also the start of the checkers chain and if the provided string
     * isn't marked with an '[]' array notation it will pass the control to
     * the JSON checker method.
     *
     * @see ParameterValue::checkJsonValue()
     *
     * @param string $value The value to parse
     *
     * @return array|mixed
     */
    private function checkArrayValue($value)
    {
        if (substr($value, 0, 1) == '[' && substr($value, -1) == ']') {
            // Array values
            $values = explode(',', substr($value, 1, -1));
            $array = array();
            foreach ($values AS $innerValue) {
                $array[] = $this->analise(trim($innerValue));
            }
            return $array;
        }
        return $this->checkJsonValue($value);
    }

    /**
     * Analyse value to parse json notation objects
     *
     * This method retrieves json objects. Example:
     * {"a":1,"b":2} will become  Object(stdClass): {a=>1, b=>2}
     *
     * It is also the start of the checkers chain and if the provided string
     * isn't marked with an '{}' json notation it will pass the control to
     * the boolean checker method.
     *
     * @param string $value The value to parse
     *
     * @return object|mixed
     */
    private function checkJsonValue($value)
    {
        if (substr($value, 0, 1) == '{' && substr($value, -1) == '}') {
            // If is json object that start with { } decode them
            return json_decode($value);
        }
        return $this->checkBooleanValue($value);
    }

    /**
     * Analyse value to parse boolean values
     *
     * @param string $value The value to parse
     *
     * @return bool|mixed
     */
    private function checkBooleanValue($value)
    {
        if (strtolower($value) == 'true') {
            return true;
        } else if (strtolower($value) == 'false') {
            return false;
        }
        return $this->checkNullValue($value);
    }

    /**
     * Analyse value to parse null values
     *
     * @param string $value The value to parse
     *
     * @return null|mixed
     */
    private function checkNullValue($value)
    {
        if (strtolower($value) == 'null') {
            return null;
        }
        return $this->checkNumberValue($value);
    }

    /**
     * Analyse value to parse null values
     *
     * @param string $value The value to parse
     *
     * @return int|float|mixed
     */
    private function checkNumberValue($value)
    {
        if (is_numeric($value)) {
            return $this->checkNumberType($value);
        }
        return $value;
    }

    /**
     * Numeric value, determine if int or float and then cast
     *
     * @param mixed $val
     * @return float|int
     */
    private static function checkNumberType($val)
    {
        if ((float) $val == (int) $val) {
            return (int) $val;
        }
        return (float) $val;
    }
}