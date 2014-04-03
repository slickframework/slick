<?php

/**
 * Checkbox element test case
 *
 * @package   Test\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form\Element;
use Slick\Form\Element\Checkbox;

/**
 * Checkbox element test case
 *
 * @package   Test\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CheckboxTest extends \Codeception\TestCase\Test
{

    /**
     * Use the checkbox element
     * @test
     */
    public function useCheckBox()
    {
        $checkbox = new Checkbox();
        $this->assertInstanceOf(
            'Slick\Form\Template\CheckboxInput',
            $checkbox->getTemplate()
        );

        $this->assertFalse((boolean) strpos($checkbox->getHtmlAttributes(), 'checked'));
        $checkbox->setValue(true);
        $this->asserttrue((boolean) strpos($checkbox->getHtmlAttributes(), 'checked'));
    }
}