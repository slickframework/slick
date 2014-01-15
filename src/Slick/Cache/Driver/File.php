<?php

/**
 * File cache driver
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache\Driver;

use Slick\FileSystem\Folder,
    Slick\FileSystem\File as FsFile;

/**
 * File cache driver
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class File extends AbstractDriver
{

    /**
     * @readwrite
     * @var string Default files path
     */
    protected $_path = './' ;

    /**
     * @readwrite
     * @var string The directory name for cache files.
     */
    protected $_dirName = 'cache';

    /**
     * @readwrite
     * @var Folder Folder to handle the cache files
     */
    protected $_folder = null;

    /**
     * Lazy loading of folder property
     * 
     * @return Folder 
     */
    public function getFolder()
    {
        if (is_null($this->_folder)) {
            $this->_folder = new Folder(
                array(
                    'name' => "{$this->_path}/{$this->_dirName}",
                    'autoCreate' => true
                )
            );
        }
        return $this->_folder;
    }
    
    /**
     * Retrives a previously stored value.
     *
     * @param String $key     The key under witch value was stored.
     * @param mixed  $default The default value, if no value was stored before.
     * 
     * @return mixed The stored value or the default value if it was
     *  not found on service cache.
     */
    public function get($key, $default = null)
    {

    }

    /**
     * Set/stores a value with a given key.
     *
     * @param String  $key      The key where value will be stored.
     * @param mixed   $value    The value to store.
     * @param integer $duration The live time of cache in seconds.
     * 
     * @return File A sefl instance for chaining method calls.
     */
    public function set($key, $value, $duration = -999)
    {
        $duration = ($duration < 0) ? $this->_duration : $duration;
        $file = $this->getFolder()->addFile($key.".tmp");
        $content = time() + $duration . "\n";
        $content .= serialize($value);
        $file->write($content);
        return $this;
    }

    /**
     * Erase the value stored wit a given key.
     *
     * @param String $key The key under witch value was stored.
     * 
     * @return File A sefl instance for chaining method calls.
     */
    public function erase($key)
    {

    }
}