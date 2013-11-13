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
		parent::append($tag);
	}

	public function offsetSet($offsetSet, $value)
	{
		if (is_a($value, 'Slick\Common\Inspector\Tag')) {
			parent::offsetSet($offsetSet, $value);
		}
	}

	public function hasTag($name)
	{
		/** @var $tag \Slick\Common\Inspector\Tag $tag */
		foreach ($this as $tag) {
			if (strtolower($name) == strtolower($tag->name)) {
				return true;
			}
		}

		return false;
	}

	public function getTag($name)
	{
		foreach ($this as $tag) {
			if (strtolower($name) == strtolower($tag->name)) {
				return $tag;
			}
		}
		return false;
	}
}