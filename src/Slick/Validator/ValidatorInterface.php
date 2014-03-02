<?php

/**
 * ValidatorInterface
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator;


/**
 * ValidatorInterface
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ValidatorInterface
{

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value);

    /**
     * Returns an array of messages that explain why the most recent
     * isValid() call returned false. The array keys are validation failure
     * message identifiers, and the array values are the corresponding
     * human-readable message strings.
     *
     * @return array
     */
    public function getMessages();

    /**
     * Sets a custom message for a given identifier
     *
     * @param string $identifier
     * @param string $message
     *
     * @return ValidatorInterface
     */
    public function setMessage($identifier, $message);
} 