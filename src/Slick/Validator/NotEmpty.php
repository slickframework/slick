<?php

/**
 * NotEmpty validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator;

/**
 * NotEmpty validator
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class NotEmpty extends AbstractValidator implements ValidatorInterface
{

    /**
     * @readwrite
     * @var array Message templates
     */
    protected $_messageTemplates = [
        'notEmpty' => 'The value cannot be empty.'
    ];

    /**
     * Returns true if and only if $value is not empty
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = preg_match('/(.+)/i', $value);
        if (!$result) {
            $this->addMessage('notEmpty');
        }
        return (boolean) $result;
    }
}