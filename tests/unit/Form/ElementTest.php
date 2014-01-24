<?php

/**
 * Elemetn test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form;

use Codeception\Util\Stub;
use Slick\Form\Element;

/**
 * Elemetn test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ElementTest extends \Codeception\TestCase\Test
{

    /**
     * Test elemetn creation
     * @test
     */
    public function createElement()
    {
        $element = new Element('username');
        $this->assertInstanceOf('Slick\Form\ElementInterface', $element);
    }
}