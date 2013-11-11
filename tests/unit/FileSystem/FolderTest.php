<?php

/**
 * Folder test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */
namespace FileSystem;

use Codeception\Util\Stub;
use Slick\FileSystem\Folder,
    Slick\FileSystem\File;

/**
 * Folder test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FolderTest extends \Codeception\TestCase\Test
{
    /**
     * @var string The path for tests
     */
    protected $_path;

    /**
     * Set the path for the test
     */
    protected function _before()
    {
        parent::_before();
        $this->_path = dirname(dirname(__DIR__)) . '/app/Temp';
    }

    /**
     * Create a folder
     * @test
     */
    public function createAFolder()
    {
        $folder = new Folder(array('name' => $this->_path .'/tests'));
        $exists = is_dir($this->_path .'/tests');
        $this->assertTrue($exists);
        if ($exists) {
            rmdir($this->_path .'/tests');
        }
    }

    /**
     * Compare two folders
     * @test
     */
    public function compareTwoFolders()
    {
        $folder = new Folder(array('name' => $this->_path .'/tests'));
        $other = new Folder(array('name' => $this->_path .'/tests'));
        $obj = new \StdClass();
        $this->assertFalse($folder->equals($obj));
        $this->assertTrue($other->equals($folder));
        $exists = is_dir($this->_path .'/tests');
        if ($exists) {
            rmdir($this->_path .'/tests');
        }
    }

    /**
     * Delete a folder
     * @test
     */
    public function deleteAFolder()
    {
        $folder = new Folder(array('name' => $this->_path .'/tests'));
        $this->assertTrue($folder->delete());
        $this->assertFalse(is_dir($this->_path .'/tests'));
    }

    /**
     * Nodes should be an iterator
     * @test
     */
    public function checkNodesIterator()
    {
        $folder = new Folder(array('name' => $this->_path .'/..'));
        $nodes = $folder->getNodes();
        $this->assertInstanceOf('\FilesystemIterator', $nodes);
    }

    /**
     * Checks the return of FileSystemList
     * @test
     */
    public function checkFolderIterator()
    {
        $folder = new Folder(array('name' => $this->_path .'/..'));
        $file = new File($folder->details->getRealPath() . '/test.txt');
        foreach ($folder->nodes as $node) {
            $this->assertInstanceOf('Slick\FileSystem\Node', $node);
            if ($node->details->isDir()) {
                $this->assertInstanceOf('Slick\FileSystem\Folder', $node);
            } else {
                $this->assertInstanceOf('Slick\FileSystem\File', $node);
            }
        }
        $file->delete();
    }

    /**
     * Check, add and get files.
     * @test
     */
    public function manipulateFiles()
    {
        $folder = new Folder(array('name' => $this->_path));
        $file = $folder->file('example.txt');
        $this->assertTrue($folder->hasFile('example.txt'));
        $this->assertFalse($folder->hasFile('other-example.txt'));
        $this->assertInstanceOf('Slick\FileSystem\File', $file);
        $file->delete();
    }

    /**
     * Check, add and get files.
     * @test
     */
    public function manipulateFolders()
    {
        $base = new Folder(array('name' => $this->_path));
        $folder = $base->folder('example');

        $this->assertTrue($base->hasFolder('example'));
        $this->assertFalse($base->hasFolder('other-example'));
        $this->assertInstanceOf('Slick\FileSystem\Folder', $folder);

        $folder->delete();
        $base->delete();
    }

}