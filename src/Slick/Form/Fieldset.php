<?php

/**
 * Fieldset
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

/**
 * Fieldset
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Fieldset extends AbstractFieldset implements FieldsetInterface
{

    /**
     * Returns current error messages
     *
     * @return array
     */
    public function getMessages()
    {
        $messages = [];
        /** @var Element $element */
        foreach ($this->_elements as $element) {
            $messages[$element->getName()] = $element->getMessages();
        }
        return $messages;
    }
}