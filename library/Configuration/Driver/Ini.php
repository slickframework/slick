<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Driver;

use Slick\Configuration\ConfigurationInterface;
use Slick\Configuration\Exception;

/**
 * Ini file type configuration driver
 *
 * @package Slick\Configuration\Driver
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Ini extends AbstractDriver implements ConfigurationInterface
{

    /**
     * Loads the data into this configuration driver
     */
    protected function load()
    {
        $data = @parse_ini_file($this->file);
        if ($data === false) {
            throw new Exception\ParserErrorException(
                "Error parsing configuration file {$this->file}"
            );
        }
        $this->data = $data;
    }
}