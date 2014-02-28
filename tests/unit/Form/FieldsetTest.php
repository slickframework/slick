<?php

/**
 * Fieldset test case
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

/**
 * Fieldset test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FieldsetTest extends \Codeception\TestCase\Test
{

    /**
     * Add elements to the fieldset
     * @test
     */
    public function addElements()
    {
        $username = new Element(['name' => 'username']);
        $password = new Element(['name' => 'password']);
        $fullName = new Element(['name' => 'fullName']);
        $fieldset = new Fieldset();
        $fieldset->add($username)
            ->add($password)
            ->add($fullName, 10);
        $fieldset->elements->next();

        $data = $fieldset->elements->current();
        $this->assertEquals($fullName, $data);
        $this->assertEquals(10, $fieldset->elements->weight());
        $elements = $fieldset->getElements();
        $this->assertFalse($fieldset->has('name'));
        $this->assertTrue($fieldset->has('password'));
        $this->assertTrue($fieldset->remove('password'));

        $this->assertEquals(2, count($fieldset->elements));
        $this->assertInstanceOf('Slick\Form\Fieldset', $fieldset->setElements($elements));
        $this->assertEquals($elements, $fieldset->getElements());

        $group = new Fieldset();
        $group->add(new Element(['name' => 'age']));
        $fieldset->add($group);

        $data = [
            'username' => 'fsilva',
            'password' => 'test',
            'fullName' => 'Filipe',
            'id' => '1',
            'age' => '20'
        ];

        $fieldset->populateValues($data);
        $field = $fieldset->get('username');
        $this->assertInstanceOf('Slick\Form\ElementInterface', $field);
        $this->assertEquals('fsilva', $field->getValue());

        $expected = "This is a test";
        $this->assertInstanceOf(
            'Slick\Form\Fieldset',
            $fieldset->setValue($expected)
        );
    }
}