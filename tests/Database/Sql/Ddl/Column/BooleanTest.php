<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Column\Boolean;

/**
 * Boolean columns Test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class BooleanTest extends TestCase
{

    public function testBoolean()
    {
        $boolean = new Boolean('active');
        $this->assertEquals('active', $boolean->getName());
    }
}
