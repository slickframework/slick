<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Inspector;

use Slick\Common\Inspector;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * InspectorList Test case
 *
 * @package Slick\tests\Common\Inspector
 */
class InspectorListTest extends TestCase
{

    public function testSingletonUsage()
    {
        $first = Inspector\InspectorList::getInstance();
        $this->assertSame($first, Inspector\InspectorList::getInstance());
    }

    public function testExceptionOnGetUnknownInspector()
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\InvalidArgumentException"
        );
        Inspector\InspectorList::getInstance()->get('stdClass');
    }

    public function testCheckingInspectorExistence()
    {
        $this->assertFalse(
            Inspector\InspectorList::getInstance()->has('stdClass')
        );
    }
}