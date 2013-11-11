<?php

/**
 * Folder
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\FileSystem;

/**
 * Folder - Represents a folder node in a given file system
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Folder extends Node
{
	
	/**
	 * @read
	 * @var \FilesystemIterator The list of folder objects
	 */
	protected $_nodes = null;

	/**
	 * @readwrite
	 * @var boolean
	 */
	protected $_autoCreate = true;

	/**
	 * @readwrite
	 * @var string Folder full path and name.
	 */
	protected $_name = null;

	/**
	 * Override the base constructor to set and/or create the directory
	 *
	 * @see  Slick\Common\Base::__construct()
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);
		
		if (!is_dir($this->name) && $this->isAutoCreate()) {
			mkdir($this->name);
		}

		$this->_details = new \SplFileInfo($this->name);
	}

	/**
	 * Returns an interator for this folder nodes
	 * 
	 * @return \FilesystemIterator A list of folder objects
	 */
	public function getNodes()
	{
		if (is_null($this->_nodes)) {
			$this->_nodes = new \FilesystemIterator(
				$this->details->getRealPath()
			);
		}
		return $this->_nodes;
	}

}