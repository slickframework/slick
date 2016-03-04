<?php

/**
 * Common methods to work with Flash Messages
 *
 * @package   Slick\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Libs\Session;

/**
 * Common methods to work with Flash Messages
 *
 * @package   Slick\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property FlashMessages $flashMessages
 */
trait FlashMessageMethods
{

    /**
     * @readwrite
     * @var FlashMessages
     */
    protected $_flashMessages;

    /**
     * Add an info flash message
     *
     * @param string $message
     * @return self
     */
    public function addInfoMessage($message)
    {
        $this->getFlashMessages()->set(
            FlashMessages::TYPE_INFO,
            $message
        );
        return $this;
    }

    /**
     * Add a warning flash message
     *
     * @param string $message
     * @return self
     */
    public function addWarningMessage($message)
    {
        $this->getFlashMessages()->set(
            FlashMessages::TYPE_WARNING,
            $message
        );
        return $this;
    }

    /**
     * Add an error flash message
     *
     * @param string $message
     * @return self
     */
    public function addErrorMessage($message)
    {
        $this->getFlashMessages()->set(
            FlashMessages::TYPE_ERROR,
            $message
        );
        return $this;
    }

    /**
     * Add a success flash message
     *
     * @param string $message
     * @return self
     */
    public function addSuccessMessage($message)
    {
        $this->getFlashMessages()->set(
            FlashMessages::TYPE_SUCCESS,
            $message
        );
        return $this;
    }

    /**
     * Returns the flash messages object
     *
     * @return FlashMessages
     */
    public function getFlashMessages()
    {
        if (is_null($this->_flashMessages)) {
            $this->_flashMessages = FlashMessages::getInstance();
        }
        return $this->_flashMessages;
    }

    /**
     * Sets a flash message to be displayed
     *
     * @param int $type
     * @param string $message
     *
     * @return self
     */
    public function setMessage($type, $message)
    {
        $this->getFlashMessages()->set($type, $message);
        return $this;
    }
}
