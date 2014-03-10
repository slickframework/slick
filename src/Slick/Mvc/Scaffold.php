<?php

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;

use Slick\Filter\StaticFilter;
use Slick\Mvc\Libs\Session\FlashMessages;
use Slick\Mvc\Libs\Utils\Pagination;
use Slick\Mvc\Scaffold\Form,
    Slick\Template\Template,
    Slick\Utility\Text;

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Scaffold extends Controller
{
    /**
     * @readwrite
     * @var Controller
     */
    protected $_controller;

    /**
     * @read
     * @var array supported scaffold actions
     */
    protected $_scaffoldActions = [
        'index', 'show', 'add', 'update', 'delete'
    ];

    /**
     * @readwrite
     * @var string The model name for this controller
     */
    protected $_modelName;

    /**
     * Overrides default constructor to set the controller and view names
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $nameParts = explode("\\", get_class($this->_controller));
        $this->_modelName = 'Models\\' . Text::singular(end($nameParts));
        $this->set('modelPlural', strtolower(end($nameParts)));
        $this->set('modelSingular', strtolower(Text::singular(end($nameParts))));
        $this->_controllerName = 'scaffold';
        Template::appendPath(__DIR__ . '/Scaffold/Views');
    }

    /**
     * Creates a new scaffold controller
     *
     * @param Controller $instance
     * @param array $options
     *
     * @return Scaffold
     */
    public static function getController(
        Controller $instance, array $options = [])
    {
        $options = array_merge(['controller' => $instance], $options);
        return new static($options);
    }

    /**
     * Handles the call to index page
     */
    public function index()
    {
        $pagination = new Pagination();
        $options = array();
        $pagination->setTotal(call_user_func_array([$this->_modelName, 'count'], array($options)));
        $options['limit'] = $pagination->rowsPerPage;
        $options['page'] = $pagination->offset;
        $records = call_user_func_array([$this->_modelName, 'all'], array($options));
        $this->set(compact('records', 'pagination'));
    }

    /**
     * Handles the request to show a record
     *
     * @param int $id
     */
    public function show($id=0)
    {
        $record = call_user_func_array([$this->_modelName, 'get'], array($id));
        if (!$record) {
            $this->setMessage(
                FlashMessages::TYPE_WARNING,
                "The specified ".
                $this->get('modelSingular') ." does not exists."
            );
            $this->redirect($this->get('modelPlural') .'/index');
        }
        $this->set(compact('record'));
    }

    /**
     * Handles the request to add a new record
     */
    public function add()
    {
        $name = "add-". $this->get('modelSingular');
        $form = new Form($name, ['model' => $this->_modelName]);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $class = $this->_modelName;
                /** @var Model $object */
                $object = new $class($form->getValues());
                if ($object->save()) {
                    $this->setMessage(
                        FlashMessages::TYPE_SUCCESS,
                        ucfirst($this->get('modelSingular')) .
                        " successfully created."
                    );
                    $this->redirect(
                        $this->get('modelPlural') .'/show/'.
                        $object->getConnector()->getLastInsertId()
                    );
                }
            } else {
                $this->setMessage(
                    FlashMessages::TYPE_ERROR,
                    ucfirst($this->get('modelSingular')) .
                    " cannot be created. Please check the errors below."
                );
            }
        }
        $this->set(compact('form'));
    }

    /**
     * Handles the request to edit a record
     *
     * @param int $id
     */
    public function edit($id=0)
    {
        $name = "edit-". $this->get('modelSingular');
        $form = new Form($name, ['model' => $this->_modelName]);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $class = $this->_modelName;
                /** @var Model $object */
                $object = new $class($form->getValues());
                if ($object->save()) {
                    $this->setMessage(
                        FlashMessages::TYPE_SUCCESS,
                        ucfirst($this->get('modelSingular')) .
                        " successfully updated."
                    );
                    $this->redirect($this->get('modelPlural') .'/index');
                }
            } else {
                $this->setMessage(
                    FlashMessages::TYPE_ERROR,
                    ucfirst($this->get('modelSingular')) .
                    " cannot be updated. Please check the errors below."
                );
            }
        } else {
            /** @var Model $record */
            $record = call_user_func_array([$this->_modelName, 'get'], array($id));
            $form->setData($record->getData());
        }

        $this->set(compact('record', 'form'));
    }

    /**
     * Handles the request to delete a record
     */
    public function delete()
    {
        if ($this->request->isPost()) {
            $id = StaticFilter::filter('text', $this->request->getPost('id'));
            $record = call_user_func_array([$this->_modelName, 'get'], array($id));
            if (!$record) {
                $this->setMessage(
                    FlashMessages::TYPE_WARNING,
                    "The specified ".
                    $this->get('modelSingular') ." does not exists."
                );
            } else {
                if ($record->delete()) {
                    $this->setMessage(
                        FlashMessages::TYPE_SUCCESS,
                        ucfirst($this->get('modelSingular')) .
                        " successfully deleted."
                    );
                }
            }

        }
        $this->redirect($this->get('modelPlural') .'/index');
    }

} 