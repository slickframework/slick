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

	public $name;

	public $value;

	protected $_raw;

	public function __construct($name, $value = true)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public function setRawValue($raw)
	{
		$this->_raw = $raw;
		$this->_parse();
	}

	protected function _parse()
	{
		$value = ArrayMethods::clean(
            ArrayMethods::trim(Text::split($this->_raw, ","))
        );
        
        $elements = count($value);

        if ($elements > 1) {
        	$this->value = new \ArrayIterator($this->_parseValue($value));
        } else if ($elements == 1) {
        	$this->value = reset($value);
        }
	}

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

	public function __call($method, $rags)
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
}