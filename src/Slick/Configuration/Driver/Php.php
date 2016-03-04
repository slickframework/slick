<?php

/**
 * PHP arrays
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Configuration\Driver;

use Slick\Configuration\Exception;

/**
 * PHP arrays
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Php extends AbstractDriver implements DriverInterface
{

    /**
     * Loads the data into this configuration driver
     */
    protected function _load()
    {
        $this->_data = @include($this->_file);
        if (!$this->_data) {
            throw new Exception\ParserErrorException(
                "Error parsing configuration file {$this->_file}"
            );
        }
    }
}
