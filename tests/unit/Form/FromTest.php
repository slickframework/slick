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
use Slick\Filter\StaticFilter;
use Slick\Form\Element;
use Slick\Form\Fieldset;
use Slick\Form\Form;
use Slick\Form\InputFilter\InputFilter;
use Slick\Validator\StaticValidator;

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

        $inputFilter = new InputFilter();
        $form->setInputFilter($inputFilter);
        $this->assertSame($inputFilter, $form->getInputFilter());
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

    /**
     * Create form and validate data
     * @test
     */
    public function validateFormData()
    {
        $name = new Element(['name' => 'name']);
        $name->getInput()->getValidatorChain()
            ->add(StaticValidator::create('notEmpty'));
        $name->getInput()->getFilterChain()
            ->add(StaticFilter::create('text'));

        $mail = new Element(['name' => 'mail']);
        $mail->getInput()->getValidatorChain()
            ->add(StaticValidator::create('email'))
            ->add(StaticValidator::create('notEmpty'));

        $fieldset = new Fieldset(['name' => 'fieldset']);
        $fieldset->add($mail);
        $form = new Form("testForm");
        $form->add($name);
        $form->add($fieldset);
        $data = ['name' => '<b>foo</b>', 'mail' => 'foo@example.com'];
        $form->setData($data);

        $this->assertTrue($form->isValid());
        $this->assertEquals('foo', $form->get('name')->getValue());

        $expected = [
            'name' => 'foo',
            'mail' => 'foo@example.com'
        ];
        $this->assertEquals($expected, $form->getValues());

        $data = ['name' => '<b></b>', 'mail' => ''];
        $form->setData($data);

        $this->assertFalse($form->isValid());
        $messages = [
            'name' => [
                'notEmpty' => 'The value cannot be empty.'
            ],
            'mail' => [
                'email' => 'The value is not a valid e-mail address.',
                'notEmpty' => 'The value cannot be empty.'
            ]
        ];
        $this->assertEquals($messages, $form->getMessages());

        $this->assertEquals($messages['mail'], $form->get('fieldset')->getMessages()['mail']);
    }
}