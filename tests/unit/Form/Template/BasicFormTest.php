<?php

/**
 * BasicForm test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form\Template;

use Slick\Form\Template\BasicForm;
use Slick\Template\Template;

/**
 * BasicForm test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BasicFormTest extends \Codeception\TestCase\Test
{
    /**
     * Set an existing template to a form
     * @test
     */
    public function setElementTemplate()
    {
        $form = new BasicForm();
        $template = new Template(['engine' => 'twig']);
        $template = $template->initialize();
        $this->assertInstanceOf(
            'Slick\Form\Template\BasicForm',
            $form->setTemplate($template)
        );
        $this->assertSame($template, $form->getTemplate());
    }
}