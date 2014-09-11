<?php

/**
 * Html entities filter test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Filter;

use Slick\Filter\StaticFilter;

/**
 * Html entities filter test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class HtmlTags extends \Codeception\TestCase\Test
{

    /**
     * Filter html entities
     * @test
     */
    public function filterHtmlEntities()
    {
        $expected = "Is Peter &lt;smart&gt; &amp; funny?";
        $this->assertEquals(
            $expected,
            StaticFilter::filter('htmlEntities', 'Is Peter <smart> & funny?')
        );
    }
} 