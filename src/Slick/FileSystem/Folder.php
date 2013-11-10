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
 * Folder is a directory in file system
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Folder extends Node
{

	/**
	 * @read
	 * @var \Slick\FileSystem\FileList A list of files.
	 */
	protected $_files = null;

	/**
	 * Sets current node base path.
	 * 
	 * @param string $path The new node full path
	 *
	 * @return \Slick\FileSystem\Node A sefl instance for method call chains
	 */
	abstract public function setPath($path)
	{
		$fullPath = rtrim($path, '/') .'/'. $this->_name;
		$this->_exists = file_exists($fullPath);
		$this->_writable = is_writable($fullPath);
	}

	/**
	 * Returns the list of files in this folder
	 * @return [type]
	 */
	public function getFiles()
	{
		if (!is_a($this->_files, 'Slick\FileSystem\FileList')) {
			$this->_loadFiles();
		}
		return $this->_elements;
	}

	/**
	 * Reads local files and creates the lit of files for this folder
	 */
	protected function _loadFiles()
	{
		$list = new FileList();
		$directory = $this->getFullPath();

		$fileNames = scandir($directory);

		foreach ($fileNames as $fileName) {
			if (!is_dir($fileName)) {
				$list->add(new File($directory .'/'. $fileName));
			}
		}
		$this->_files = $list;
	}
}