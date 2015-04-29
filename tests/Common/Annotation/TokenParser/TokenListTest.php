<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Annotation\TokenParser;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Annotation\TokenParser\Token;
use Slick\Common\Annotation\TokenParser\TokenList;

/**
 * Token list test case
 *
 * @package Slick\Tests\Common\Annotation\TokenParser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenListTest extends TestCase
{

    /**
     * @var TokenList|Token[]
     */
    protected $tokens;

    /**
     * Example code
     * @var string
     */
    protected $code = <<<EOC
<?php

/**
 * Test code to be parsed into a token list
 */
namespace Slick\Test;

use Slick\Test\Class as Alias;
use Slick\Test\SomeClass;
EOC;

    /**
     * Create the list
     */
    protected function setup()
    {
        parent::setUp();
        $this->tokens = new TokenList($this->code);
    }

    /**
     * Clean up after each test
     */
    protected function tearDown()
    {
        $this->tokens = null;
        parent::tearDown();
    }

    public function testCountTokens()
    {
        $this->assertEquals(32, $this->tokens->count());
    }

    public function testIteratorIsAListOfTokens()
    {
        foreach ($this->tokens as $token) {
            $this->assertInstanceOf(
                "Slick\\Common\\Annotation\\TokenParser\\Token",
                $token
            );
        }
    }
}
