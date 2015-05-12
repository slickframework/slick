<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Fixtures;

use PDO;

/**
 * Class Mock PDO
 *
 * @package Slick\Tests\Database\Fixtures
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MockPDO extends PDO
{

    public $arguments = [];

    /**
     * Let us create mock of PDO class
     */
    public function __construct ()
    {
        $this->arguments = func_get_args();
        $this->connect();
    }

    public function connect()
    {}

    public function setAttribute ($attribute, $value)
    {}
}