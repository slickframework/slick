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
        	$this->value = new \ArrayIterator($value);
        } else if ($elements == 1) {
        	$this->value = reset($value);
        }


	}
}