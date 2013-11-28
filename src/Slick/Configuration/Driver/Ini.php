<?php

/**
 * Ini
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Configuration\Driver;

use Slick\FileSystem\File,
    Slick\Configuration\Exception;

/**
 * Ini file type configuration driver
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Ini extends AbstractDriver implements DriverInterface
{
    /**
     * Loads the data into this configuration driver
     */
    protected function _load()
    {
        $file = new File($this->_file, "r");

        $data = @parse_ini_string($file->read(), true);
        if ($data === false) {
            throw new Exception\ParserErrorException(
                "Error parsing configuration file {$this->_file}"
            );
        }
        $this->_data = $data;
    }
}