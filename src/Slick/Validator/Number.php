<?php

/**
 * Number  validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator;

/**
 * Number  validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Number extends AbstractValidator implements ValidatorInterface
{

    /**
     * @readwrite
     * @var array Message templates
     */
    protected $_messageTemplates = [
        'number' => 'The value is not a number.'
    ];

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = filter_var($value, FILTER_VALIDATE_INT);
        if (!$result) {
            $this->addMessage('number');
        }
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}