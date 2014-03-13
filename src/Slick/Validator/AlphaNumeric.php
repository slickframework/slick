<?php

/**
 * AlphaNumeric validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator;

/**
 * AlphaNumeric validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AlphaNumeric extends AbstractValidator implements ValidatorInterface
{

    /**
     * @readwrite
     * @var array Message templates
     */
    protected $_messageTemplates = [
        'alphaNumeric' => 'The value can only contain alpha numeric characters.'
    ];

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = (boolean) preg_match('/^([0-9a-zA-Z\-]+)$/i', $value);
        if (!$result) {
            $this->addMessage('alphaNumeric');
        }
        return $result;
    }
}