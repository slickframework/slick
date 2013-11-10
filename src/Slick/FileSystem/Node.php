<?php

/**
 * Node
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\FileSystem;

use Slick\Common\Base;

/**
 * Node is a generic entity on a given file system
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $name Node name
 * @property string $path Node path in file system
 * @property boolean $exists Does the node exists on the given path
 * @property boolean $writable The node is writable
 *
 * @method {boolean} isWritable() isWritable() Check if the node is writable
 * @method {string} getName() getName() Returns the node name
 * @method {string} getPath() getPath() Returns the node path 
 */
abstract class Node extends Base
{

	/**
	 * @readwrite
	 * @var string Node name
	 */
	protected $_name;

	/**
	 * @readwrite
	 * @var string Node path in file system
	 */
	protected $_path;

	/**
	 * @read
	 * @var boolean Does the node exists on the given path
	 */
	protected $_exists = false;

	/**
	 * @read
	 * @var boolean The node is writable
	 */
	protected $_writable = false;

	/**
	 * Overrides default constructor to accept only the node path and name.
	 *
	 * Name can be a full path to node. If so you don't need to provide a path
	 * for this node.
	 * 
	 * @param string $name The node name
	 * @param string $path The node full path
	 */
	protected function __construct($name, $path = null)
	{
		$fullPath = str_replace(array('//', '\\'), '/', $path .'/'. $name);
		$parts = explode('/', $fullPath);
		$name = array_pop($parts);
		$path = '/' . implode('/', $parts);

		parent::__construct(array('name' => $name, 'path' => $path));

		$this->_exists = file_exists($fullPath);
		$this->_writable = is_writable($fullPath);
	}

	/**
	 * Sets current node base path.
	 * 
	 * @param string $path The new node full path
	 *
	 * @return \Slick\FileSystem\Node A sefl instance for method call chains
	 */
	abstract public function setPath($path);

	/**
	 * Sets the current file a new name
	 * 
	 * @param string $nsme The new node name
	 *
	 * @return \Slick\FileSystem\Node A sefl instance for method call chains
	 */
	public function setName($name)
	{
		$fullPath = $this->_path .'/'. ltrim($name, '/');
		$this->_exists = file_exists($fullPath);
		$this->_writable = is_writable($fullPath);
		return $this; 
	}

	public function getFullPath()
	{
		return $this->path  .'/'. $this->name;
	}

}