<?php

/**
 * FileList
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\FileSystem;

use Slick\Utility\ArrayList;

/**
 * FileList - A list of File objects used in Folder.
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FileList extends ArrayList implements FileListInterface
{

	/**
     * Inserts the specified file at the specified position in this list
     * 
     * @param \Slick\FileSystem\File $file  file to be inserted
     * @param integer                $index Index at which the specified file
     *  is to be inserted
     *
     * @return boolean True if collection has change as a result of the call
     */
    public function add(File $file, $index = null)
    {
    	return parent::add($file, $index);
    }

    /**
     * Replaces the file at the specified position in this list with the
     * specified file
     * 
     * @param \Slick\FileSystem\File $file  File to be stored at the
     *  specified position
     * @param integer                $index Index of the file to replace
     *
     * @return \Slick\FileSystem\File File previously at the specified position
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the index
     *   is out of range (index < 0 || index >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If index is
     *   not a numeric value.
     */
    public function set(File $file, $index)
    {
    	return parent::set($file, $index);
    }
}