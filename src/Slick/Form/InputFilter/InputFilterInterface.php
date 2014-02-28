<?php
/**
 * InputFilter Interface
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\InputFilter;

/**
 * InputFilter Interface
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface InputFilterInterface
{

    /**
     * Add an input to the input filter
     *
     * If name is not set, the input name will be used to as the
     * name to retrieve it.
     *
     * @param InputInterface|InputFilterInterface $input
     * @param string $name (Optional) Name used to retrieve the input
     *
     * @return InputFilterInterface
     */
    public function add($input, $name = null);

    /**
     * Retrieves the input stored with the provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return InputInterface|InputFilterInterface
     */
    public function get($name);

    /**
     * Check if this input filter has an input with the given name
     *
     * @param string $name Name under witch input was stored
     *
     * @return boolean
     */
    public function has($name);

    /**
     * Removes the input that was stored with the provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return boolean True if an input with given name existed and was removed
     */
    public function remove($name);

    /**
     * Populate data on current input list
     *
     * @param array $data An associative array with input names and
     * corespondent values
     *
     * @return InputFilterInterface
     */
    public function setData($data);

    /**
     * Check if all data set is valid
     *
     * @return boolean
     */
    public function isValid();

    /**
     * Get filtered value of the input with provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return mixed
     */
    public function getValue($name);

    /**
     * Returns all input values filtered
     *
     * @return array An associative array with input names as keys and
     * filtered values as values
     */
    public function getValues();

    /**
     * Get raw (unfiltered) value of the input with provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return mixed
     */
    public function getRawValue($name);

    /**
     * Get all the values from data set without filtering
     *
     * @return array An associative array with input names as keys and
     * corespondent raw values
     */
    public function getRawValues();

    /**
     * Returns all error messages from last isValid call
     *
     * @return array
     */
    public function getMessages();
} 