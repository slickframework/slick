<?php

/**
 * Inspector
 * 
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Common;

/**
 * Inspector uses PHP reflection to inspect classes or objects.
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class Inspector
{

    /**
     * @var string|Object Class name or object to inspect.
     */
    protected $_class = null;

    /**
     * @var array Class metadata.
     */
    protected $_meta = array(
        "class" => array(),
        "properties" => array(),
        "methods" => array()
    );

    /**
     * @var array List of class properties
     */
    protected $_properties = array();

    /**
     * @var array List of class methods.
     */
    protected $_methods = array();

    /**
     * Constructs an inspector for a given class.
     *
     * @param string|object $class The class name or object to inspect.
     */
    public function __construct($class)
    {
        $this->_class = $class;
    }
    
    /**
     * Returns the inspected class comment
     * 
     * @see \ReflectionClass::getDocComment()
     *  http://php.net/manual/en/reflectionclass.getdoccomment.php
     *
     * @return string|false The class docblock comment or false.
     */
    protected function _getClassComment()
    {
        $reflection = new \ReflectionClass($this->_class);
        return $reflection->getDocComment();
    }
    
    /**
     * Parses the docblock comment to retrieve key/value pairs in it.
     *
     * If it finds no value component on comment it sets the key to true.
     * This is useful for flag keys as @readwrite or @once.
     * 
     * @param string $comment The comment to parse.
     * 
     * @return array A key/value(s) associative array from comment.
     */
    protected function _parse($comment)
    {
        $meta = array();
        $pattern = "(@[a-zA-Z]+\s*.*)";
        
        $matches = Text::match($comment, $pattern);
        if ($matches != null) {
            foreach ($matches as $match) {
                $parts = ArrayMethods::clean(
                    ArrayMethods::trim(Text::split($match, "[\s*]", 2))
                );

                $meta[$parts[0]] = true;

                if (sizeof($parts) > 1) {
                    $meta[$parts[0]] = ArrayMethods::clean(
                        ArrayMethods::trim(Text::split($parts[1], ","))
                    );
                }
            }
        }
        return $meta;
    }
    
    /**
     * Returns the inspected class meta data
     *
     * @return array A key/value(s) associative array from class comment.
     */
    public function getClassMeta()
    {
        $comment = $this->_getClassComment();
        return $comment;
    }

}
