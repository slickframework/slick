<?php

/**
 * StaticValidator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator;

/**
 * StaticValidator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class StaticValidator
{
    /**
     * @var array List of available validators
     */
    public static $validators = [
        'notEmpty' => 'Slick\Validator\NotEmpty',
        'email' => 'Slick\Validator\Email'
    ];

    /**
     * @var array The error messages from last validation
     */
    protected static $_messages = [];

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param string $validator The validator name
     * @param mixed $value
     *
     * @throws Exception\UnknownValidatorClassException
     * @return bool
     *
     * @see Slick\Validator\StaticValidator::$validators
     */
    public static function isValid($validator, $value)
    {
        /** @var ValidatorInterface $validator */
        $validator = static::create($validator);
        $result = $validator->isValid($value);
        static::$_messages = $validator->getMessages();
        return $result;
    }

    /**
     * Creates a validator object
     *
     * @param string $validator The validator class name or alias
     *
     * @param null $message
     * @throws Exception\UnknownValidatorClassException
     *
     * @return ValidatorInterface
     *
     */
    public static function create($validator, $message = null)
    {
        if (array_key_exists($validator, static::$validators)) {
            $class = static::$validators[$validator];
            $id = $validator;
        } else if (
        is_subclass_of($validator, 'Slick\Validator\ValidatorInterface')
        ) {
            $class = $validator;
            $id = ucfirst(str_replace("\\", "", $validator));
        } else {
            throw new Exception\UnknownValidatorClassException(
                "The validator '{$validator}' is not defined or does not " .
                "implements the Slick\\Validator\\ValidatorInterface interface"
            );
        }

        /** @var ValidatorInterface $object */
        $object = new $class;
        if (!is_null($message)) {
            $object->setMessage($id, $message);
        }

        return $object;
    }

    /**
     * Returns an array of messages that explain why the most recent
     * isValid() call returned false. The array keys are validation failure
     * message identifiers, and the array values are the corresponding
     * human-readable message strings.
     *
     * @return array
     */
    public static function geMessages()
    {
        return static::$_messages;
    }
} 