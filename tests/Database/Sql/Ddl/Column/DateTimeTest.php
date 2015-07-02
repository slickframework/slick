<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Column\DateTime;

/**
 * DateTime column test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DateTimeTest extends TestCase
{

    public function testNullable()
    {
        $col = new DateTime('created');
        $result = $col->setNullable(true);
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\DateTime',
            $result
        );
        $this->assertTrue($col->getNullable());
    }
}
