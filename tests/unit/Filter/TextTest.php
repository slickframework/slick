<?php

/**
 * Text filter test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Filter;
use Slick\Filter\AbstractFilter;
use Slick\Filter\Exception;
use Slick\Filter\FilterChain;
use Slick\Filter\FilterInterface;
use Slick\Filter\StaticFilter;
use Slick\Filter\Text;

/**
 * ext filter test case
 *
 * @package    Test\Filter
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class TextTest extends \Codeception\TestCase\Test
{

    /**
     * Filter text only
     * @test
     * @expectedException \Slick\Filter\Exception\UnknownFilterClassException
     */
    public function filterText()
    {
        $html = '<b><span></span>Hello world "From text"</b><div>';
        $textOnly = new Text();
        $expected = 'Hello world "From text"';
        $this->assertEquals(
            $expected,
            $textOnly->filter($html)
        );

        $this->assertEquals($expected, StaticFilter::filter('text', $html));

        $this->assertTrue(StaticFilter::filter('Filter\MyFilter', $html));

        StaticFilter::filter('unknown', $html);
    }

    /**
     * Chain filters
     * @test
     */
    public function chainFilters()
    {
        $html = '<b><span></span>Hello world '.PHP_EOL.'"From text"</b><div>';
        $chain = new FilterChain();
        $chain->add(new Text())->add(new NewLines());
        $expected = 'Hello world <br />'.PHP_EOL.'"From text"';
        $this->assertEquals($expected, $chain->filter($html));
    }
}

/**
 * Dummy class for tests
 */
class MyFilter extends AbstractFilter implements FilterInterface
{

    /**
     * Returns the result of filtering $value
     *
     * @param mixed $value
     *
     * @throws Exception\CannotFilterValueException If filtering $value
     * is impossible
     *
     * @return mixed
     */
    public function filter($value)
    {
        return (boolean) $value;
    }
}

class NewLines extends AbstractFilter implements FilterInterface
{

    /**
     * Returns the result of filtering $value
     *
     * @param mixed $value
     *
     * @throws Exception\CannotFilterValueException If filtering $value
     * is impossible
     *
     * @return mixed
     */
    public function filter($value)
    {
        return nl2br($value);
    }
}