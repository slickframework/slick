<?php

/**
 * URL Validator test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Validator;
use Slick\Validator\Url;


/**
 * URL Validator test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class UrlTest extends \Codeception\TestCase\Test
{

    /**
     * Validate URL
     * @test
     */
    public function validateUrl()
    {
        $url = "some text";
        $urlValidator = new Url();
        $this->assertFalse($urlValidator->isValid($url));
    }
}