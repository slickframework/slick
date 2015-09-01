<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

use ReflectionClass;
use Slick\Common\Base;
use Slick\Configuration\Exception;

/**
 * Factory class to create configuration driver objects
 *
 * @package Slick\Configuration
 *
 * @property string $type Configuration driver class name
 * @property string $file The file containing the configuration data
 */
final class Configuration extends Base
{

    /**@#+
     * Known configuration drivers
     */
    const DRIVER_INI = 'Slick\Configuration\Driver\Ini';
    const DRIVER_PHP = 'Slick\Configuration\Driver\Php';
    /**@#- */

    private static $interface = 'Slick\Configuration\Driver\DriverInterface';

    /**
     * @var string[] A list of available paths where configuration file are in
     */
    private static $paths = ['./'];

    /**
     * @var array Extensions for driver file types
     */
    private static $extensions = [
        self::DRIVER_PHP => '.php',
        self::DRIVER_INI => '.ini'
    ];

    /**
     * @readwrite
     * @var string
     */
    protected $type = self::DRIVER_PHP;

    /**
     * @readwrite
     * @var string
     */
    protected $file;

    /**
     * Returns a configuration driver
     *
     * @return ConfigurationInterface
     */
    public function initialize()
    {
        $this->checkClass();
        return new $this->type(['file' => $this->file]);
    }

    /**
     * Prepends a searchable path to available paths list.
     *
     * @param string $path
     */
    public static function addPath($path)
    {
        $path = str_replace('//', '/', rtrim($path, '/'));
        if (!in_array($path, self::$paths)) {
            array_unshift(self::$paths, $path);
        }
    }

    /**
     * Factory method to search and parse the provided name
     *
     * @param string $file the file name
     * @param string $type parser class
     *
     * @return ConfigurationInterface
     */
    public static function get($file, $type = self::DRIVER_PHP)
    {
        $configuration = new Configuration(
            [
                'type' => $type,
                'file' => self::getFileName($file, $type)
            ]
        );
        return $configuration->initialize();
    }

    /**
     * Check for files in the defined paths
     *
     * @param string $file
     * @param string $type
     *
     * @return string
     */
    private static function getFileName($file, $type)
    {
        $extension = array_key_exists($type, self::$extensions)
            ? self::$extensions[$type]
            : null;

        foreach (self::$paths as $path) {
            $fileName = "{$path}/{$file}{$extension}";
            if (file_exists($fileName)) {
                $file = $fileName;
                break;
            }
        }
        return $file;
    }

    /**
     * Check if type is a valid configuration driver
     */
    private function checkClass()
    {
        if (!class_exists($this->type)) {
            throw new Exception\InvalidArgumentException(
                "Configuration class '{$this->type}' not found"
            );
        }

        $reflection = new ReflectionClass($this->type);
        if (!$reflection->implementsInterface(self::$interface)) {
            throw new Exception\InvalidArgumentException(
                "Class '{$this->type}' does not implement ".
                "Slick\\Configuration\\ConfigurationInterface"
            );
        }
    }
}
