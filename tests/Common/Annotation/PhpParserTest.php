<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Annotation;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Annotation\PhpParser;

/**
 * Php Parser Test case
 *
 * @package Slick\tests\Common\Annotation
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class PhpParserTest extends testCase
{

    /**
     * Test parse class for use statements
     */
    public function testClassParser()
    {
        $parser = new PhpParser();
        $reflection = new \ReflectionClass(
            'Slick\Tests\Common\Annotation\Fixtures\UseStatements'
        );
        $this->assertEquals(
            [
                'AnnotationFactory' => 'Slick\Common\Annotation\Factory',
                'Basic' => 'Slick\Common\Annotation\Basic'
            ],
            $parser->parseClass($reflection)
        );
    }
}
