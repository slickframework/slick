<?php

/**
 * ClassMetaData
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
 * ClassMetaData parses and stores class metada
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ClassMetaData
{

	/**
	 * @var \Slick\Common\TagList Metadata tags for current class.
	 */
	protected $_class = null;

	/**
	 * @var \ArrayIterator A hash map for property list
	 */
	protected $_properties = null;

	/**
	 * @var \ArrayIterator A hash map for method list
	 */
	protected $_methods = null;

	/**
	 * @var \ReflectionClass Reflection object for current inspected class
	 */
	private $_reflection = null;

	/**
	 * Creats a ClassMetaData with a provided class reflection object
	 * 
	 * @param \ReflectionClass $reflection Reflection object for current inspected class
	 */
	public function __construct(\ReflectionClass $reflection)
	{
		$this->_reflection = $reflection;
		$this->_properties = new \ArrayIterator();
		$this->_methods = new \ArrayIterator();
	}

	/**
	 * Returns the list of tags for current inspected class
	 * 
	 * @return \Slick\Common\TagList The list of tags of inspected class.
	 */
	public function getClassTags()
	{
		if (is_null($this->_class)) {
			$this->_class = new TagList();
			$comment = $this->_reflection->getDocComment();
			$this->_parse($comment, $this->_class);
		}
		return $this->_class;
	}

	/**
	 * Parses the docblock comment to retrieve anotaion tags like @tag.
	 * 
	 * @param string                $comment The comment doc block
	 * @param \Slick\Common\TagList $list    The list where to add the
	 *  parsed tags.
	 */
	protected function _parse($comment, \Slick\Common\Inspector\TagList &$list)
	{
		$meta = array();
        $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9\\\,=\s\.\@\<\>_\-]*)";
        
        $matches = Text::match($comment, $pattern);
        if ($matches != null) {
            foreach ($matches as $match) {
                $parts = ArrayMethods::clean(
                    ArrayMethods::trim(Text::split($match, "[\s*]", 2))
                );

                $tag = new Tag($parts[0]);

                if (sizeof($parts) > 1) {
                    $tag->setRawValue($parts[1]);
                }
                
                $list->append($tag);
            }
        }
	}

}