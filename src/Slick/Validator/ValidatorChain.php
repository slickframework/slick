<?php

/**
 * ValidatorChain
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator;

use Slick\Common\Base;

/**
 * ValidatorChain
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ValidatorChain extends Base implements ValidatorInterface
{

    /**
     * @readwrite
     * @var ValidatorInterface[]
     */
    protected $_validators = [];

    /**
     * @readwrite
     * @var array
     */
    protected $_messages = [];

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $messages = [];
        $valid = true;
        foreach ($this->_validators as $validator) {
            if (!$validator->isValid($value)) {
                $valid = false;
                $messages = array_merge($messages, $validator->getMessages());
            }
        }
        return $valid;
    }

    /**
     * Returns an array of messages that explain why the most recent
     * isValid() call returned false. The array keys are validation failure
     * message identifiers, and the array values are the corresponding
     * human-readable message strings.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * Adds a validator to the chain
     *
     * @param ValidatorInterface $validator
     *
     * @return ValidatorChain
     */
    public function add(ValidatorInterface $validator)
    {
        $this->_validators[] = $validator;
        return $this;
    }
}