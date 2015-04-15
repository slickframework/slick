<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Annotation;

use Slick\Common\Annotation\Parser;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Comment parser test case
 *
 * @package Slick\tests\Common\Annotation
 */
class ParserTest extends TestCase
{

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var string
     */
    protected $multiTagComment = <<<EOC
/**
 * This is NOT an empty tag comment.
 * @tag1
 * @tag2 someTag
 * @tag3 otherTag, with=parameters
 */
EOC;

    /**
     * @var string
     */
    protected $simpleComment = <<<EOC
/**
 * This is an empty tag comment.
 */
EOC;


    /**
     * @covers Slick\Common\Annotation\Parser::__constructor()
     */
    protected function setup()
    {
        parent::setUp();
        $this->parser = new Parser();
    }

    /**
     * Tear down after each test
     */
    protected function tearDown()
    {
        $this->parser = null;
        parent::tearDown();
    }

    /**
     * @covers Slick\Common\Annotation\Parser::getTags()
     */
    public function testParseEmptyTagsComment()
    {

        $this->parser->setComment($this->simpleComment);
        $this->assertEquals([], $this->parser->getTags());
    }

    /**
     * @covers Slick\Common\Annotation\Parser::getTags()
     */
    public function testParseMultipleTagsComment()
    {
        $expected = [
            ["@tag1\n", 'tag1'],
            ["@tag2 someTag\n", 'tag2', 'someTag'],
            [
                "@tag3 otherTag, with=parameters\n */",
                'tag3',
                'otherTag, with=parameters'
            ],
        ];

        $this->parser->setComment($this->multiTagComment);
        $this->assertEquals($expected, $this->parser->getTags());
    }

    /**
     * @covers Slick\Common\Annotation\Parser::setComment()
     * @covers Slick\Common\Annotation\Parser::__construct()
     */
    public function testCommentSetterResetTags()
    {
        $tags = $this->parser->setComment($this->simpleComment)
            ->getTags();
        $this->assertCount(0, $tags);
    }

    public function testSimpleAnnotationTag()
    {
        $this->parser->setComment($this->multiTagComment);
        $expected = [
            'tag1' => true,
            'tag2' => ['someTag' => true, 'raw' => 'someTag'],
            'tag3' => [
                'otherTag' => true,
                'with' => 'parameters',
                'raw' => 'otherTag, with=parameters'
            ],
        ];
        $this->assertEquals($expected, $this->parser->getAnnotationData());
    }
}
