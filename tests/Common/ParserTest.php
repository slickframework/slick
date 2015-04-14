<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common;

use Slick\Common\Annotation\Parser;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Comment parser test case
 *
 * @package Slick\Tests\Common
 */
class ParserTest extends TestCase
{

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @covers Parser::__constructor
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
        $comment = <<<EOF
/**
 * This is an empty tag comment.
 */
EOF;
        $this->parser->setComment($comment);
        $this->assertEquals([], $this->parser->getTags());
    }

    /**
     * @covers Slick\Common\Annotation\Parser::getTags()
     */
    public function testParseMultipleTagsComment()
    {
        $comment = <<<EOF
/**
 * This is NOT an empty tag comment.
 * @tag someTag
 * @tag otherTag, with=parameters
 */
EOF;
        $expected = [
            ["@tag someTag\n", 'tag', 'someTag'],
            ["@tag otherTag, with=parameters\n */", 'tag', 'otherTag, with=parameters'],
        ];

        $this->parser->setComment($comment);
        $this->assertEquals($expected, $this->parser->getTags());
    }
}
