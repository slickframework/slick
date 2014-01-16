<?php

/**
 * FileSystemList
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\FileSystem;

/**
 * FileSystemList is an iterator that returns a Slick\FileSystem Node
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FileSystemList extends \FilesystemIterator
{

    /**
     * Returns current element
     * 
     * @return \Slick\FileSystem\Node A File or a Folder object.
     */
    public function current()
    {
        $fileInfo = parent::current();
        if ($fileInfo->isDir()) {
            return new Folder(array('name' => $fileInfo->getRealPath()));
        }
        return new File($fileInfo->getRealPath());
    }
}