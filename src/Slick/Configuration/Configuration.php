<?php

/**
 * Configuration
 *
 * @package   Slick\Configuration
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Configuration;

use Slick\Common\Base;
use Slick\Configuration\Driver\DriverInterface;

/**
 * Factory class to create configuration drivers
 *
 * @package   Slick\Configuration
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $class Driver class or driver class alias.
 * @property array  $options Options that will be passed to the driver class.
 * @method Configuration setClass(string $class) Sets the driver class or
 * driver class alias to initialize.
 * @method string getClass() Returns the driver class or driver class alias.
 * @method Configuration setOptions(array $options) Sets the options that
 * will be passed to the driver class when instantiating.
 * @method array getOptions() Returns the current set of driver options.
 */
class Configuration extends Base
{
    
    /**
     * @readwrite
     * @var string Configuration type or driver class name.
     */
    protected $_class = 'ini';

    /**
     * @readwrite
     * @var array A list of options for Configuration Driver.
     */
    protected $_options;

    /**
     * @var string[] a list of available paths
     */
    protected static $_paths = ['./'];

    /**
     * Factory method to search and parse the provided name
     *
     * @param string $file the file name
     * @param string $class parser class
     *
     * @throws Exception\FileNotFoundException if the given file was not found
     * in any of the paths defined in Configuration::$_paths static property
     *
     * @return DriverInterface
     */
    public static function get($file, $class='ini')
    {
        foreach (self::$_paths as $path) {
            $fileName = "{$path}/{$file}.{$class}";

            if (is_file($fileName)) {
                $cfg = new Configuration(
                    [
                        'class' => $class,
                        'options' => [
                            'file' => $fileName
                        ]
                    ]
                );
                return $cfg->initialize();
            }
        }

        throw new Exception\FileNotFoundException(
            "The file {$file}.{$class} was not found in the configuration " .
            "directories list. Search on the following directories:" .
            implode(', ', self::$_paths) . "."
        );
    }

    /**
     * Prepends a searchable path to available paths list.
     *
     * @param string $path
     */
    public static function addPath($path)
    {
        $path = str_replace('//', '/', rtrim($path, '/'));
        if (!in_array($path, self::$_paths)) {
            array_unshift(self::$_paths, $path);
        }
    }

    /**
     * Returns the current configured paths where configuration files live in
     *
     * @return string[]
     */
    public static function getPathList()
    {
        return self::$_paths;
    }

    /**
     * Initializes the driver specified by class using the provided options.
     *
     * @return DriverInterface
     *
     * @throws Exception\InvalidArgumentException If the class provided is not
     * known, is not defined or if an existing class is provided it does not
     * implements the {@see \Slick\Configuration\Driver\DriverInterface}
     * interface
     */
    public function initialize()
    {
        $class = $this->class;
        $driver = null;

        if (empty($class)) {
            throw new Exception\InvalidArgumentException(
                "The configuration driver is invalid."
            );
        }

        // Load user defined driver
        if (class_exists($class)) {
            $driver = new $class($this->_options);
            if (is_a($driver, '\Slick\Configuration\Driver\DriverInterface')) {
                return $driver;
            } else {
                throw new Exception\InvalidArgumentException(
                    "The configuration type '{$class}' doesn't inherited from "
                    ."Slick\Configuration\Driver\DriverInterface."
                );
            }
        }

        switch ($class) {
            case 'ini':
                $driver = new Driver\Ini($this->_options);
                break;
            
            default:
                throw new Exception\InvalidArgumentException(
                    "The configuration driver is unknown."
                );
        }

        return $driver;
    }
}
