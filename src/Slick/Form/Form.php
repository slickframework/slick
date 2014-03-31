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

use Slick\Di\DependencyInjector,
    Slick\Form\InputFilter\InputFilter,
    Slick\Form\Template\AbstractTemplate,
    Slick\Form\Template\BasicForm;
use Slick\I18n\TranslateMethods;
use Zend\EventManager\EventManagerAwareInterface,
    Zend\EventManager\EventManagerInterface,
    Zend\EventManager\SharedEventManager,
    Zend\EventManager\EventManager;

/**
 * Form
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property InputFilter $inputFilter
 * @property Factory $factory
 */
class Form extends AbstractFieldset
    implements FormInterface, EventManagerAwareInterface
{

    /**
     * @readwrite
     * @var array
     */
    protected $_data;

    /**
     * @readwrite
     * @var EventManager
     */
    protected $_events;

    /**
     * @readwrite
     * @var InputFilter
     */
    protected $_inputFilter;

    /**
     * @readwrite
     * @var Factory
     */
    protected $_factory;

    /**
     * Adds translate methods to this class
     */
    use TranslateMethods;

    /**
     * @readwrite
     * @var array
     */
    protected $_attributes =[
        'action' => '',
        'method' => 'post'
    ];

    /**
     * Overrides the parent constructor to set name as mandatory param.
     *
     * @param string       $name
     * @param array|object $options
     */
    public function __construct($name, $options = array())
    {
        parent::__construct($options);
        $this->setName($name);
        $this->getEventManager()->trigger(
            'formBeforeSetup',
            $this,
            array(
                'name' => $name
            )
        );
        $this->_setup();
        $this->getEventManager()->trigger(
            'formAfterSetup',
            $this,
            array(
                'name' => $name
            )
        );
    }

    /**
     * Callback for form setup
     */
    protected function _setup()
    {
    }

    /**
     * Adds an element to the form using the built in factory
     *
     * @param string $name Element name
     * @param array $data Factory data
     *
     * @return Form A self instance for method call chain
     *
     * @throws Exception\UnknownElementException
     */
    public function AddElement($name, $data)
    {
        $this->getEventManager()->trigger(
            'formBeforeAddElement',
            $this,
            array(
                'name' => $name,
                'data' => &$data
            )
        );
        $this->factory->addElement($this, $name, $data);
        $this->getEventManager()->trigger(
            'formBeforeAddElement',
            $this,
            array(
                'name' => $name,
                'data' => &$data
            )
        );
        return $this;
    }

    /**
     * Lazy loads and returns form factory object
     *
     * @return Factory
     */
    public function getFactory()
    {
        if (is_null($this->_factory)) {
            $this->factory = new Factory();
        }
        return $this->_factory;
    }

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

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return Form
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_called_class(),
                $this->getName()
            )
        );
        $this->_events = $eventManager;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (is_null($this->_events)) {
            $injector = DependencyInjector::getDefault();
            /** @var SharedEventManager $sharedEvent */
            $sharedEvent = $injector->get('DefaultEventManager');
            $events = new EventManager();
            $events->setSharedManager($sharedEvent);
            $this->setEventManager($events);
        }
        return $this->_events;
    }

    /**
     * Sets form input filter
     *
     * @param InputFilter $inputFilter
     *
     * @return Form
     */
    public function setInputFilter(InputFilter $inputFilter)
    {
        $this->_inputFilter = $inputFilter;
        return $this;
    }

    /**
     * lazy loads this for input filter
     *
     * @return InputFilter
     */
    public function getInputFilter()
    {
        if (is_null($this->_inputFilter)) {
            $this->_inputFilter = new InputFilter();
        }
        return $this->_inputFilter;
    }
    /**
     * Adds an element to the list
     *
     * @param ElementInterface $object
     * @param int $weight
     *
     * @return Form
     */
    public function add(ElementInterface $object, $weight = 0)
    {
        parent::add($object, $weight);
        $this->_addInput($object);
    }

    /**
     * @param array|Fieldset|Element $elements
     */
    protected function _addInput($elements)
    {
        if (is_a($elements, 'Slick\Form\FieldsetInterface')) {
            $this->_addInput($elements->elements->asArray());
        } else {
            if (is_array($elements)) {
                /** @var Element $element */
                foreach ($elements as $element) {
                    $this->getInputFilter()->add($element->getInput());
                }
            } else {
                /** @var Element $element */
                $this->getInputFilter()->add($elements->getInput());
            }
        }
    }

    /**
     * Checks whenever the data set is valid or not
     *
     * This means that all the elements must be valid for this method
     * return boolean true, otherwise will always return false.
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->getInputFilter()->isValid();
    }

    /**
     * Returns current error messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->getInputFilter()->getMessages();
    }

    /**
     * Returns all input values filtered
     *
     * @return array An associative array with input names as keys and
     * filtered values as values
     */
    public function getValues()
    {
        return $this->getInputFilter()->getValues();
    }

    /**
     * lazy loads a default template for this element
     *
     * @return AbstractTemplate
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            $this->setTemplate(new BasicForm());
        }
        return $this->_template;
    }

    /**
     * Returns the attributes as they will be used in the HTML output
     * @return string
     */
    public function getHtmlAttributes()
    {
        $result = parent::getHtmlAttributes();
        return trim(str_replace('form-control', '', $result));
    }

    /**
     * Renders the form as HTML string
     *
     * @return string The HTML output string
     */
    public function render()
    {
        $this->getEventManager()->trigger(
            'formBeforeRender',
            $this,
            array(

            )
        );
        $output = parent::render();
        $this->getEventManager()->trigger(
            'formAfterRender',
            $this,
            array(
                'output' => &$output
            )
        );
        return $output;
    }
}