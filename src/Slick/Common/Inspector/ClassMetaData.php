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
     * @param \ReflectionClass $reflection Reflection object for current
     *  inspected class
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
     * Returns the list of tags for provided property name
     * 
     * @param string $name The property name to inspect.
     * 
     * @return \Slick\Common\TagList The list of tags of inspected property
     */
    public function getPropertyMeta($name)
    {
        if (!isset($this->_properties[$name])) {
            $this->_properties[$name] = new TagList();
            $comment = $this->_reflection
                ->getProperty($name)
                ->getDocComment();
            if (!empty($comment)) {
                $this->_parse($comment, $this->_properties[$name]);
            }
        }
        return $this->_properties[$name];
    }

    /**
     * Returns the list of tags for provided method name
     * 
     * @param string $name The name of the method to be inspected
     * 
     * @return \Slick\Common\TagList The list of tags of inspected method
     */
    public function getMethodMeta($name)
    {
        if (!isset($this->_methods[$name])) {
            $this->_methods[$name] = new TagList();
            $comment = $this->_reflection
                ->getMethod($name)
                ->getDocComment();
            if (!empty($comment)) {
                $this->_parse($comment, $this->_methods[$name]);
            }
        }
        return $this->_methods[$name];
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