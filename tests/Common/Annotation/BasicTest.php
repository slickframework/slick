<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Annotation;

use Slick\Common\Annotation\Basic;
use Slick\Common\Annotation\Parser;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Basic annotation test case
 *
 * @package Slick\tests\Common\Annotation
 */
class BasicTest extends TestCase
{

    public function testSimpleTag()
    {
        $annotation = new Basic('test', true);
        $this->assertEquals('test', $annotation->getName());
    }

    public function testSingleValueTag()
    {
        $annotation = new Basic('test',
            [
                'someTag' => true,
                'raw' => 'someTag'
            ]
        );
        $this->assertEquals('someTag', $annotation->getValue());
    }

    public function testMultipleValueTag()
    {
        $annotation = new Basic('test',
            [
                'otherTag' => true,
                'with' => 'parameters',
                'raw' => 'otherTag, with=parameters'
            ]
        );
        $this->assertEquals('parameters', $annotation->getParameter('with'));
    }

    public function testCommonTags()
    {
        $comment = <<<EOC
/**
 * @author Filipe Silva <silvam.filipe@gmail.com>
 */
EOC;
        $commentData = new Parser($comment);
        $commentData = $commentData->getAnnotationData();

        $annotation = new Basic('author',$commentData['author']);
        $this->assertNull($annotation->getParameter('test'));
    }
}
