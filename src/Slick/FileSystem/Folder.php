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
 *
 * @property \SplFileInfo $details
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
        
        if (!is_dir($this->_name) && $this->isAutoCreate()) {
            mkdir($this->_name);
        }

        $this->_details = new \SplFileInfo($this->_name);
    }

    /**
     * Returns an interator for this folder nodes
     * 
     * @return \Slick\FileSystem\FileSystemList A list of folder objects
     */
    public function getNodes()
    {
        if (is_null($this->_nodes)) {
            $this->_nodes = new FileSystemList(
                $this->details->getRealPath()
            );
        }
        return $this->_nodes;
    }

    /**
     * Adds a file to current folder
     * 
     * @param string $name The file name to add
     * @param string $mode The file opening mode
     *
     * @return \Slick\FileSystem\File The added file object.
     */
    public function addFile($name, $mode = 'c+')
    {
        $path = $this->details->getRealPath();
        return new File($path .'/'. ltrim($name, '/'), $mode);
    }

    /**
     * Adds a folder to current folder
     * 
     * @param string $name The folder name to add
     *
     * @return \Slick\FileSystem\Folder The added folder object.
     */
    public function addFolder($name)
    {
        $path = $this->details->getRealPath();
        return new Folder(
            array(
                'name' => $path .'/'. ltrim($name, '/')
            )
        );
    }

    /**
     * Searches for file existence in current folder.
     * 
     * @param string $name The file name to check
     * 
     * @return boolean True if file exists in current folder.
     */
    public function hasFile($name)
    {
        foreach ($this->nodes as $node) {
            if ($node->details->getFilename() == $name
                && !$node->details->isDir()
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Searches for folder existence in current folder.
     * 
     * @param string $name The folder name to check
     * 
     * @return boolean True if folder exists in current folder.
     */
    public function hasFolder($name)
    {
        foreach ($this->nodes as $node) {
            if ($node->details->getFilename() == $name
                && $node->details->isDir()
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the file with the given name.
     *
     * If file does not exists it will be created.
     * 
     * @param string $name The file name
     * @param string $mode The file opening mode
     *
     * @return \Slick\FileSystem\File The file object.
     */
    public function getFile($name, $mode = 'r')
    {
        return $this->addFile($name, $mode);
    }

    /**
     * Gets the folder with the given name.
     *
     * If folder doesn't exists it will be created.
     * 
     * @param string $name The folder name
     *
     * @return \Slick\FileSystem\File The folder object.
     */
    public function getFolder($name)
    {
        return $this->addFolder($name);
    }

}