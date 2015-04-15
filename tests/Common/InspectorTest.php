<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Inspector;

/**
 * Inspector Test case
 *
 * @package Slick\tests\Common
 */
class InspectorTest extends TestCase
{

    public function testUniquenessOfInspectors()
    {
        $inspector = Inspector::forClass($this);
        $this->assertSame($inspector, Inspector::forClass($this));
    }

    public function testClassAnnotations()
    {
        $inspector = Inspector::forClass($this);
        $annotations = $inspector->getClassAnnotations();
        $annotation  = $annotations->getAnnotation('@package');
        $this->assertEquals('Slick\tests\Common', $annotation->getValue());
    }
}