<?php

/**
 * Form test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */
namespace Form;
use Slick\Form\Element;
use Slick\Form\Fieldset;
use Slick\Form\Form;

/**
 * Form test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FromTest extends \Codeception\TestCase\Test
{

    /**
     * Getting the event manager
     * @test
     */
    public function getFormEventManager()
    {
        $form = new Form("TestForm");
        $eventManager = $form->getEventManager();
        $this->assertContains('TestForm', $eventManager->getIdentifiers());
    }

    /**
     * Set form data
     * @test
     */
    public function setFormData()
    {
        $name = new Element(['name' => 'name']);
        $mail = new Element(['name' => 'mail']);
        $fieldset = new Fieldset(['name' => 'fieldset']);
        $fieldset->add($mail);
        $form = new Form("testForm");
        $form->add($name);
        $form->add($fieldset);
        $data = ['name' => 'foo', 'mail' => 'foo@example.com'];
        $form->setData($data);
        $formName = $form->get('name');
        $this->assertInstanceOf('Slick\Form\ElementInterface', $formName);
        $this->assertEquals($data['name'], $form->get('name')->getValue());
        $this->assertEquals($data['mail'], $form->get('fieldset')->get('mail')->getValue());
    }
}