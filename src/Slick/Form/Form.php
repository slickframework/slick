<?php

/**
 * Form
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

/**
 * Form
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Form extends AbstractFieldset implements FormInterface
{

    /**
     * @readwrite
     * @var array
     */
    protected $_data;

    /**
     * Set data to validate and/or populate elements
     *
     * @param  array $data
     *
     * @return FormInterface
     */
    public function setData($data)
    {
        $this->_data = $data;
        $this->populateValues($data);
        return $this;
    }
}