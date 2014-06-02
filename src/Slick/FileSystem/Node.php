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
 * Node represents a entry (file/folder) in a given file system path.
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property \SplFileInfo $details
 */
abstract class Node extends Base
{

    /**
     * @read
     * @var \SplFileObject The file object information
     */
    protected $_details = null;

    /**
     * @readwrite
     * @var boolean A flag for automatic node creation.
     */
    protected $_autoCreate = true;

    /**
     * Deletes current node.
     * 
     * @return boolean True if current node was deleted successfully.
     */
    public function delete()
    {
        if ($this->details->isDir()) {
            return @rmdir($this->details->getRealPath());
        }
        return @unlink($this->details->getRealPath());
    }

    /**
     * Compares current object with provided one for equality
     * 
     * @param mixed|object $object The object to compare with
     * 
     * @return boolean True if the provided object is equal to this object
     */
    public function equals($object)
    {
        if (!is_a($object, '\Slick\FileSystem\Node')) {
            return false;
        }

        $path = $this->details->getRealPath();

        return $path == $object->details->getRealPath();
    }
}