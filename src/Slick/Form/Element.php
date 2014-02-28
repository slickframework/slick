<?php

/**
 * Element
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;
use Slick\Form\InputFilter\Input;
use Slick\Form\InputFilter\InputAwareInterface;
use Slick\Form\InputFilter\InputInterface;

/**
 * Element
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Element extends AbstractElement implements ElementInterface, InputAwareInterface
{

    /**
     * @readwrite
     * @var InputInterface
     */
    protected $_input;

    /**
     * @readwrite
     * @var array
     */
    protected $_attributes = [
        'type' => 'text'
    ];

    /**
     * Sets the input object
     *
     * @param InputInterface $input
     *
     * @return Element
     */
    public function setInput(InputInterface $input)
    {
        $this->_input = $input;
        return $this;
    }

    /**
     * Lazy loads the input fot this object
     *
     * @return Input
     */
    public function getInput()
    {
        if (is_null($this->_input)) {
            $this->_input = new Input($this->getName());
        }
        return $this->_input;
    }

    /**
     * Sets element default value
     *
     * @param string $value
     *
     * @return Element
     */
    public function setValue($value)
    {
        $this->_value = $value;
        $this->getInput()->setValue($value);
        return $this;
    }

    /**
     * Returns current error messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->getInput()->getMessages();
    }

    /**
     * Returns the element value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->getInput()->getValue();
    }

}