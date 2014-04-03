<?php

/**
 * InputAwareInterface
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\InputFilter;

/**
 * InputAwareInterface
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface InputAwareInterface
{

    /**
     * Sets the input object
     *
     * @param InputInterface $input
     *
     * @return InputAwareInterface
     */
    public function setInput(InputInterface $input);

    /**
     * Lazy loads the input fot this object
     *
     * @return InputInterface
     */
    public function getInput();
} 