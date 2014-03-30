<?php

/**
 * Area element test case
 *
 * @package   Test\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form\Element;
use Slick\Form\Element\Area;

/**
 * Area element test case
 *
 * @package   Test\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AreaTest extends \Codeception\TestCase\Test
{

    /**
     * Use the area element
     * @test
     */
    public function useAreaElement()
    {
        $area = new Area();
        $this->assertInstanceOf(
            'Slick\Form\Template\AreaInput',
            $area->getTemplate()
        );
    }
}