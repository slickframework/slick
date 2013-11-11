<?php

/**
 * File test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace FileSystem;

use Slick\FileSystem\File;

/**
 * File test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FileTest extends \Codeception\TestCase\Test
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
     * Create a file
     * @test
     * @expectedException Slick\FileSystem\Exception\OpenFileException
     */
    public function createAFile()
    {
        $file = new File($this->_path .'/example.txt');
        $exists = is_file($this->_path .'/example.txt');
        $this->assertTrue($exists);
        $this->assertInstanceOf('\SplFileObject', $file->details);
        if ($exists) {
            unlink($this->_path .'/example.txt');
        }

        $test = new File("/test/other.txt", "x+");
    }

    /**
     * Delete a file
     * @test
     */
    public function deleteAFile()
    {
        $file = new File($this->_path .'/example.txt');
        $this->assertTrue(is_file($this->_path .'/example.txt'));
        $this->assertTrue($file->delete());
        $this->assertFalse(is_file($this->_path .'/example.txt'));
        $file->delete();
    }

    /**
     * Compare to files for equality
     * @test
     */
    public function compareAFile()
    {
        $obj = new \StdClass();
        $file = new File($this->_path .'/example.txt');
        $otherFile = new File($this->_path .'/example.txt');

        $this->assertFalse($file->equals($obj));
        $this->assertTrue($file->equals($otherFile));
        $file->delete();
        $otherFile->delete();
    }

    /**
     * Retreive a folder from file object
     * @test
     */
    public function retrieveFileFolder()
    {
        $file = new File($this->_path .'/example.txt');
        $this->assertTrue(is_file($this->_path .'/example.txt'));
        $folder = $file->folder;
        $this->assertInstanceOf('Slick\FileSystem\Folder', $folder);
        $file->delete();
    }

    /**
     * Read and write file content
     * @test
     */
    public function readWriteContent()
    {
        $file = new File($this->_path .'/example.txt');
        $content = "Hello world";
        $this->assertTrue($file->write($content));
        $this->assertEquals($content, $file->read());
        $content .= PHP_EOL . "New line";
        $this->assertTrue($file->write("New line", File::MODE_APPEND));
        $this->assertEquals($content, $file->read());
        $content = "First line" . PHP_EOL . $content;
        $this->assertTrue($file->write("First line", File::MODE_PREPEND));
        $file->delete();
    }

}