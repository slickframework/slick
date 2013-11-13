<?php

/**
 * TagList
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common\Inspector;

/**
 * TagList manages a list of doc block tags
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TagList extends \ArrayIterator implements TagListInterface
{

	public function append(\Slick\Common\Inspector\Tag $tag)
	{
		$this[$tag->name] = $tag;
	}

	public function offsetSet($offsetSet, $value)
	{
		if (is_a($value, 'Slick\Common\Inspector\Tag')) {
			parent::offsetSet($offsetSet, $value);
		}
	}

	public function hasTag($name)
	{
		return $this->offsetExists($name);
	}

	public function getTag($name)
	{
		if ($this->offsetExists($name)) {
			return $this[$name];
		}		
		return false;
	}
}