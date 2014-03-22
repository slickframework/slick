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
 * File represents a single file in file system
 *
 * @package   Slick\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class File extends Node
{

    /**#@+
     * @const string MODE constant names
     */
    const MODE_APPEND = 1;
    const MODE_PREPEND = 2;
    const MODE_REPLACE = 3;
    /**#@-*/

    /**
     * @read
     * @var \Slick\FileSystem\Folder This node's folder
     */
    protected $_folder = null;

    /**
     * Construct a new file object
     *
     * A URL can be used as a filename with this function if the fopen wrappers
     * have been enabled. See fopen() for more details on how to specify the
     * filename. See the Supported Protocols and Wrappers for links to
     * information about what abilities the various wrappers have, notes on
     * their usage, and information on any predefined variables they may
     * provide.
     * 
     * @param string   $name           The node (File/Folder) to read.
     * @param string   $openMode       The mode in which to open the file.
     * @param boolean  $useIncludePath Whether to search in the include_path
     *  for name.
     *
     * @see http://www.php.net/manual/en/function.fopen.php See fopen() for a
     *  list of allowed modes and wrappers.
     *
     * @throws \Slick\FileSystem\Exception\OpenFileException If the name cannot
     *  be opened.
     */
    public function __construct(
        $name, $openMode = "c+", $useIncludePath = false)
    {
        parent::__construct(array());

        try {
            $this->_details = new \SplFileObject(
                $name,
                $openMode,
                $useIncludePath
            );
        } catch (\RuntimeException $exc) {
            $msg = $exc->getMessage();
            throw new Exception\OpenFileException(
                "Error while open/creating file system node '{$name}': {$msg}",
                1,
                $exc
            );
            
        }
    }

    /**
     * Retrurs this file folder.
     * 
     * @return \Slick\FileSystem\Folder The folder object for this file.
     */
    public function getFolder()
    {
        if (is_null($this->_folder)) {
            $this->_folder = new Folder(
                array(
                    'name' => $this->details->getPath()
                )
            );
        }
        return $this->_folder;
    }

    /**
     * Write content to this file
     * 
     * @param string  $content The content to write
     * @param integer $mode    [description]
     * 
     * @return boolean True if content was added to file.
     */
    public function write($content, $mode = self::MODE_REPLACE)
    {
        $fileContent = $this->read();
        switch ($mode) {
            case self::MODE_PREPEND:
                $fileContent = $content . PHP_EOL . $fileContent;
                break;

            case self::MODE_APPEND:
                $fileContent .= PHP_EOL . $content;
                break;

            case self::MODE_REPLACE:
            default:
                $fileContent = $content;
        }

        $result = file_put_contents(
            $this->details->getRealPath(),
            $fileContent
        );

        return $result !== false;
    }

    /**
     * Reads the content of this file
     *
     * This method may return Boolean FALSE, but may also return a
     * non-Boolean value which evaluates to FALSE. Use the === operator
     * for testing the return value of this method.
     * 
     * @return string The content of this file.
     */
    public function read()
    {
        $file = $this->details->getRealPath();
        return file_get_contents($file);
    }

    public function exists()
    {

    }
}
