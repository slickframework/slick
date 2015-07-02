<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Column\AbstractColumn;

/**
 * Class AbstractColumnTest
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractColumnTest extends TestCase
{

    /**
     * Should return the name provided in constructor
     * @test
     */
    public function getName()
    {
        /** @var AbstractColumn $sut */
        $sut = $this->getMockBuilder(
            'Slick\Database\Sql\Ddl\Column\AbstractColumn'
        )
            ->setConstructorArgs(['testColumn'])
            ->getMockForAbstractClass();
        $this->assertEquals('testColumn', $sut->getName());
    }
}
