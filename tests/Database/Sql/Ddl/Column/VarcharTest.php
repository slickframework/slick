<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Column\Varchar;

/**
 * Varchar column test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class VarcharTest extends TestCase
{

    public function testVarChar()
    {
        $var = new Varchar('name', 255);
        $this->assertEquals(255, $var->getLength());
    }
}
