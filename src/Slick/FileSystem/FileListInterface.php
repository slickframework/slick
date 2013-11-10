<?php

/**
 * FileList Interface
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\FileSystem;

use Slick\Utility\Collections\ListInterface;

/**
 * FileListInterface changes the List interface to accept only File objets
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface FileListInterface extends ListInterface
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
    public function add(File $file, $index = null);

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
    public function set(File $file, $index);
}