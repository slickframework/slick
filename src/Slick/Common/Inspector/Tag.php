<?php

/**
 * Tag
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common\Inspector;

use Slick\Utility\ArrayMethods,
    Slick\Utility\Text;

/**
 * Tag defines a entry in the doc block 
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Tag
{
    /**
     * @var string The tag name
     */
    public $name;

    /**
     * @var TagValues The tag value
     */
    public $value;

    /**
     * @var string the raw value as it appears in doc block
     */
    protected $_raw;

    /**
     * Constructor - sets the name and value of this tag
     * 
     * @param string         $name  The tag name
     * @param boolean|string $value The tag value
     */
    public function __construct($name, $value = true)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Sets the tag raw value and parses its real value
     * 
     * @param string $raw The tag value as it appears in the docblock
     *
     * @return \Slick\Common\Inspector\Tag A self instance for method
     *  call chains
     */
    public function setRawValue($raw)
    {
        $this->_raw = $raw;
        $this->_parse();
        return $this;
    }

    /**
     * A magic method to retrieve named values
     *
     * If a tag has named values, like name1=value1, name2=value2, ... 
     * you can retrieve those values using the get<Name>() magic method.
     * For example $this->getName1() will return "value1" while
     * $this->getOtherName() will return null.
     * 
     * @param string $method The method called
     * @param array  $args   The arguments that were used in the call
     * 
     * @return null|string The named value or null if name doesn't exists
     * 
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __call($method, $args)
    {
        $getMatches = Text::match($method, "^get([a-zA-Z0-9\_]+)$");

        if (sizeof($getMatches) > 0) {
            $index = strtolower($getMatches[0]);
            if ($this->value->offsetExists($index)) {
                return $this->value[$index];
            }               
        }
        
        return null;
    }

    /**
     * Parses the raw value to retrieve the real value
     */
    protected function _parse()
    {
        $value = ArrayMethods::clean(
            ArrayMethods::trim(Text::split($this->_raw, ","))
        );
        
        $elements = count($value);
        if ($elements > 1) {
            $this->value = new TagValues($this->_parseValue($value));
        } else if ($elements == 1 && strpos($value[0], '=') !== false) {
            $this->value = new TagValues($this->_parseValue($value));
        } else {
            $this->value = reset($value);
        }
    }

    /**
     * Helper method to retrieve named values
     * 
     * @param array $value The raw value part to parse
     * 
     * @return array An array containing name/value pairs of values entered
     *  in the doc block tag value.
     */
    protected function _parseValue($value)
    {
        $elements = array();
        foreach ($value as $prop) {
            $split = ArrayMethods::trim(Text::split($prop, "[=*]", 2));
            if (count($split) > 1) {
                $elements[strtolower($split[0])] = $split[1];
            } else {
                $elements[] = $prop;
            }
        }
        return $elements;
    }

}