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

use Slick\Common\Exception;

/**
 * TagList manages a list of doc block tags
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TagList extends \ArrayIterator
{

	/**
	 * Appends a new Tag to the end to the list
	 * 
	 * @param  \Slick\Common\Inspector\Tag $tag The tag to appen
	 * 
	 * @return \Slick\Common\Inspector\TagList A self instance for method
	 *  call chains
	 */
	public function append(\Slick\Common\Inspector\Tag $tag)
	{
		$this[$tag->name] = $tag;
		return $this;
	}

	/**
	 * Adds a tag to the provided offset position
	 * 
	 * @param string 						  $offset The name of the tag
	 *  that will be added
	 * @param \Slick\Common\Inspector\TagList $value The tag to add.
	 * 
	 * @throws \Slick\Common\Exception\InvalidArgumentException If the value
	 *  is not a Slick\Common\Inspector\Tag object
	 */
	public function offsetSet($offset, $value)
	{
		if (is_a($value, 'Slick\Common\Inspector\Tag')) {
			$offset = strtolower($value->name);
			parent::offsetSet($offset, $value);
		} else {
			throw new Exception\InvalidArgumentException(
				"Only a Slick\Common\Inspector\Tag object can be added ".
				"to a TagList"
			);
			
		}
	}

	/**
	 * Returns the value at specified offset.
	 * 
	 * @param string $offset The name of the tag to retreive
	 * 
	 * @return \Slick\Common\Inspector\Tag The tag that has the provided name
	 *  or boolean FALSE if there are no tags with the given name
	 */
	public function offsetGet($offset)
	{
		$offset = strtolower($offset);
		if (isset($this[$offset])) {
			return parent::offsetGet($offset);
		}
		return false;
	}

	/**
	 * Check if this list contains a tag with the provided name.
	 *
	 * The tag name contains the '@' as it is set in the doc block. If you
	 * are check for the 'read' tag existance use '@read' as tag name.
	 * 
	 * @param string $name The tag name to check. Ex.: @read, @type, etc.
	 * 
	 * @return boolean True if this list contains tag with the provided name.
	 */
	public function hasTag($name)
	{
		return $this->offsetExists($name);
	}

	/**
	 * Retrieves the tag with the provided name from this list
	 * 
	 * The tag name contains the '@' as it is set in the doc block. If you
	 * are check for the 'read' tag existance use '@read' as tag name.
	 * 
	 * @param string $name The tag name to check. Ex.: @read, @type, etc.
	 * 
	 * @return \Slick\Common\Inspector\Tag The tag that has the provided name
	 *  or boolean FALSE if there are no tags with the given name
	 */
	public function getTag($name)
	{
		return $this[$name];
	}
}