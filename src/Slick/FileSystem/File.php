<?php

/**
 * File
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\FileSystem;

/**
 * File is a specific file system node type
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class File extends Node
{

	/**
	 * @readwrite
	 * @var \Slick\FileSystem\Folder This file containing folder.
	 */
	protected $_folder = null;

	/**
	 * Overrides the Node constructor to set the containing folder for this file
	 * 
	 * @param string $name The node name
	 * @param string $path The node full path
	 */
	public function __construct($name, $path = null)
	{
		parent::__construct($name, $path = null);

		$this->_folder = new Folder($this->_path);
	}

	/**
	 * Sets current file base path.
	 * 
	 * @param string $path The new node full path
	 *
	 * @return \Slick\FileSystem\Node A sefl instance for method call chains
	 */
	public function setPath($path)
	{
		$fullPath = rtrim($path, '/') .'/'. $this->_name;
		$this->_exists = file_exists($fullPath);
		$this->_writable = is_writable($fullPath);
		$this->_folder = new Folder($path);
	}

	
}