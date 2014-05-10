<?php

/**
 * Extended form test case
 *
 * @package   Test\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form;

use Slick\Di\Container;
use Slick\Di\ContainerBuilder;
use Slick\Di\Definition;
use Slick\Form\Form as SlickFrom,
    Slick\Form\Element;

/**
 * Extended form test case
 *
 * @package   Test\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ExtendedFormTest extends \Codeception\TestCase\Test
{
    /**
     * Use extended form
     * @test
     */
    public function useExtendedForm()
    {
        $container = ContainerBuilder::buildContainer(
            [
                'commentForm' => Definition::object('\Form\CommentForm')
                    ->constructor(['comment-edit'])
            ]
        );
        $form = $container->get('commentForm');
        /** @var Element $element */
        $element = $form->get('body');
        $this->assertInstanceOf('Slick\Form\InputFilter\Input', $element->getInput());
        $this->assertInstanceOf('Slick\Form\InputFilter\InputFilter', $form->inputFilter);
        $this->assertTrue($form->inputFilter->has('body'));

    }
}


/**
 * CommentForm
 *
 * @package Controllers\Forms
 * @author  Your Name <your.name@email.com>
 */
class CommentForm extends SlickFrom
{

    /**
     * Form setup callback
     */
    protected function _setup()
    {
        $this
            ->addElement('id', [
                'type' => 'text',
                'label' => 'Id',
                'options' => [],
                'validate' => [
                ],
                'filter' => [
                ]
            ])
            ->addElement('body', [
                'type' => 'area',
                'label' => 'Body',
                'options' => [],
                'validate' => [
                    'notEmpty',
                ],
                'filter' => [
                    'text',
                ]
            ])
            ->addElement('post', [
                'type' => 'select',
                'label' => 'Post',
                'options' => [],
                'validate' => [
                    'notEmpty' => "This must not be empty"
                ],
                'filter' => [
                ]
            ]);

        $this->add(
            new Element\Submit(
                ['value' => 'Save']
            )
        );
    }
}
