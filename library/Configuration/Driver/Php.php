<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Driver;

use Slick\Configuration\Exception;
use Slick\Configuration\ConfigurationInterface;

/**
 * PHP arrays
 *
 * @package Slick\Configuration\Driver
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Php extends AbstractDriver implements ConfigurationInterface
{

    /**
     * Loads the data into this configuration driver
     */
    protected function load()
    {
        $this->data = @include($this->file);
        if (!$this->data) {
            throw new Exception\ParserErrorException(
                "Error parsing configuration file {$this->file}"
            );
        }
    }
}
