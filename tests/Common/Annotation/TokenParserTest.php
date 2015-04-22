<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Annotation;

use PHPUnit_Framework_TestCase as TestCase;
use ReflectionClass;
use Slick\Common\Annotation\TokenParser;
use SplFileObject;

/**
 * Token Parser test case
 *
 * @package Slick\tests\Common\Annotation
 */
class TokenParserTest extends TestCase
{
    /**
     * @var TokenParser
     */
    private $parser;

    /**
     * @var ReflectionClass
     */
    private $reflection;

    /**
     * @var TokenParser
     */
    private $multipleNamespacesParser;

    /**
     * @var ReflectionClass
     */
    private $multipleNamespacesReflection;

    /**
     * @var TokenParser
     */
    private $secondClassParser;

    /**
     * @var ReflectionClass
     */
    private $secondClassReflection;

    private $expected = [
        'TestCase' => 'PHPUnit_Framework_TestCase',
        'ReflectionClass' => 'ReflectionClass',
        'TokenParser' => 'Slick\Common\Annotation\TokenParser',
        'SplFileObject' => 'SplFileObject'
    ];

    /**
     * Creates the SUT object
     */
    protected function setup()
    {
        parent::setUp();
        $this->reflection = new ReflectionClass($this);
        $content = $this->getFileContent(
            $this->reflection->getFilename(),
            $this->reflection->getStartLine()
        );
        $this->parser = new TokenParser($content);

        // Use statements separated by commas
        $this->multipleNamespacesReflection = new ReflectionClass(
            'Slick\Tests\Common\Annotation\Fixtures\UseStatements'
        );

        $content = $this->getFileContent(
            $this->multipleNamespacesReflection->getFilename(),
            $this->multipleNamespacesReflection->getStartLine()
        );

        $this->multipleNamespacesParser = new TokenParser($content);

        // Tow classes int the same file
        $this->secondClassReflection = new ReflectionClass(
            'Slick\Tests\Common\Annotation\Fixtures\Other\TestClass'
        );

        $content = $this->getFileContent(
            $this->secondClassReflection->getFilename(),
            $this->secondClassReflection->getStartLine()
        );

        $this->secondClassParser = new TokenParser($content);
    }

    /**
     * Clean up for next test
     */
    protected function tearDown()
    {
        $this->parser = null;
        $this->multipleNamespacesParser = null;
        $this->multipleNamespacesReflection = null;
        $this->reflection = null;
        $this->secondClassReflection = null;
        $this->secondClassParser = null;
        parent::tearDown();
    }

    /**
     * Test class use statements parse
     */
    public function testClassParse()
    {
        $this->assertEquals(
            $this->expected,
            $this->parser->parseUseStatements(
                $this->reflection->getNamespaceName()
            )
        );
    }

    /**
     * Test the namespaces separated by commas
     */
    public function testCommaSeparatedUseStatements()
    {
        $this->assertEquals(
            [
                'AnnotationFactory' => 'Slick\Common\Annotation\Factory',
                'Basic' => 'Slick\Common\Annotation\Basic'
            ],
            $this->multipleNamespacesParser
                ->parseUseStatements(
                    $this->multipleNamespacesReflection
                        ->getNamespaceName()
                )
        );
    }

    /**
     * test for more then 1 namespace per file
     */
    public function testSecondClassInfile()
    {
        $this->assertEquals(
            [],
            $this->secondClassParser->parseUseStatements(
                $this->secondClassReflection->getNamespaceName()
            )
        );
    }

    /**
     * Gets the content of the file right up to the given line number.
     *
     * @param string  $filename   The name of the file to load.
     * @param integer $lineNumber The number of lines to read from file.
     *
     * @return string The content of the file.
     */
    private function getFileContent($filename, $lineNumber)
    {
        if ( ! is_file($filename)) {
            return null;
        }
        $content = '';
        $lineCnt = 0;
        $file = new SplFileObject($filename);
        while (!$file->eof()) {
            if ($lineCnt++ == $lineNumber) {
                break;
            }
            $content .= $file->fgets();
        }
        return $content;
    }
}
