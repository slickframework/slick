<?php

/**
 * Configuration test case
 * 
 * @package   Test\Configuration
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Configuration;

use Codeception\Util\Stub;
use Slick\Configuration\Configuration,
    Slick\Configuration\Driver\AbstractDriver;
use Slick\Configuration\Driver\Php;

/**
 * Configuration test case
 * 
 * @package   Test\Configuration
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ConfigurationTest extends \Codeception\TestCase\Test
{

    /**
     * Create a configuration object
     * @test
     * @expectedException \Slick\Configuration\Exception\InvalidArgumentException
     */
    public function createAConfiguration()
    {
        $conf = new Configuration();
        $this->assertInstanceOf('Slick\Configuration\Configuration', $conf);
        $conf->setClass(null);
        $conf->initialize();
    }

    /**
     * Creates an invalid configuration driver
     * @test
     * @expectedException \Slick\Configuration\Exception\InvalidArgumentException
     */
    public function createAnInvalidDriver()
    {
        $cfg = new Configuration(array('class' => 'Hi'));
        $cfg->initialize();
    }

    /**
     * Create an INI configuration type driver
     * @test
     */
    public function createAnIniDriver()
    {
        $config = new Configuration(
            array(
                'class' => 'ini',
                'options' => array(
                    'file' => __DIR__ . "/test.ini"
                )
            )
        );

        $driver = $config->initialize();
        $this->assertInstanceOf('Slick\Configuration\Driver\DriverInterface', $driver);
        $this->assertInstanceOf('Slick\Configuration\Driver\Ini', $driver);
    }

    /**
     * Load a ini file with errors
     * @test
     * @expectedException \Slick\Configuration\Exception\ParserErrorException
     */
    public function createIniWithErrors()
    {
        $config = new Configuration(
            array(
                'class' => 'ini',
                'options' => array(
                    'file' => __DIR__ . "/wrong.ini"
                )
            )
        );

        $driver = $config->initialize();
 
    }

    /**
     * Set get and set methods
     * @test
     */
    public function gettingAndSettingValues()
    {
        $config = new Configuration(
            array(
                'class' => 'ini',
                'options' => array(
                    'file' => __DIR__ . "/test.ini"
                )
            )
        );

        $driver = $config->initialize();
        $this->assertEquals(5, $driver->get('first_section.five'));
        $this->assertFalse($driver->get('first_section.other', false));
        $this->assertInstanceOf('Slick\Configuration\Driver\Ini', $driver->set('first_section.other', true));
        $this->assertTrue($driver->get('first_section.other', false));
    }

    /**
     * Set get and set methods
     * @test
     */
    public function gettingAndSettingValuesForPhp()
    {
        $config = new Configuration(
            array(
                'class' => 'php',
                'options' => array(
                    'file' => __DIR__ . "/test.php"
                )
            )
        );

        $driver = $config->initialize();
        $this->assertEquals(5, $driver->get('first_section.five'));
        $this->assertFalse($driver->get('first_section.other', false));
        $this->assertInstanceOf('Slick\Configuration\Driver\Php', $driver->set('first_section.other', true));
        $this->assertTrue($driver->get('first_section.other', false));
    }

    /**
     * Create a custom driver
     * @test
     * @expectedException \Slick\Configuration\Exception\InvalidArgumentException
     */
    public function createCustomDriver()
    {
        $class = 'Configuration\MyDriver';
        $config = new Configuration(array('class' => $class));
        $driver = $config->initialize();
        $this->assertInstanceOf($class, $driver);
        $class = 'Configuration\OtherDriver';
        $config = new Configuration(array('class' => $class));
        $driver = $config->initialize();
    }

    /**
     * Test factory static method
     * @test
     * @expectedException \Slick\Configuration\Exception\FileNotFoundException
     */
    public function getFromFactory()
    {
        Configuration::addPath(__DIR__);
        $cfg = Configuration::get('test', 'ini');
        $this->assertInstanceOf('Slick\Configuration\Driver\Ini', $cfg);
        $cfg = Configuration::get('unknown');
    }

    /**
     * Parse error on php array configuration
     * @test
     * @expectedException \Slick\Configuration\Exception\ParserErrorException
     */
    public function phpParseError()
    {
        $cfg = new Php();

    }

    /**
     * Test factory static method
     * @test
     * @expectedException \Slick\Configuration\Exception\FileNotFoundException
     */
    public function getPhpFromFactory()
    {
        Configuration::addPath(__DIR__);
        $cfg = Configuration::get('test');
        $this->assertInstanceOf('Slick\Configuration\Driver\Php', $cfg);
        $cfg = Configuration::get('unknown');
    }

}

class MyDriver extends AbstractDriver
{
    protected function _load()
    {
        $this->_data = array();
    }
}

class OtherDriver
{

}