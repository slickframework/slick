<?php

/**
 * Url validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator;

/**
 * Url validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Url extends AbstractValidator implements ValidatorInterface
{

    /**
     * @readwrite
     * @var array Message templates
     */
    protected $_messageTemplates = [
        'url' => 'The value is not a valid URL.'
    ];

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = (boolean) filter_var($value, FILTER_VALIDATE_URL);
        if (!$result) {
            $this->addMessage('url');
        }
        return $result;
    }
}