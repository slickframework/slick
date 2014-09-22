<?php

/**
 * Test controller
 */

namespace Controllers;
use Slick\Mvc\Controller;

/**
 * Class Pages
 * @package Controllers
 */
class Pages extends Controller
{

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->set('before', 'never-run');
        $this->set('once', 0);
    }

    public function home()
    {
        $param = null;
        if ($this->request->isPost()) {
            $param = $this->request->getPost('name');
        }
        $this->set(compact('param'));
    }

    public function index()
    {
        //$this->redirect('pages/home');
    }

    public function errorPage()
    {

    }

    public function changeLayout()
    {
        $this->layout = 'layouts/other';
        $this->view = 'pages/home';
    }

    /**
     * @before before, once
     * @after after, once
     */
    public function multiple()
    {
        $this->set('after', 'never-run');
    }

    public function before()
    {
        $this->set('before', 'run');
    }

    public function after()
    {
        $this->set('before', 'after-run');
    }

    /**
     * @once
     */
    public function once()
    {
        $runs = $this->get('once', 0);
        $this->set('once', ++$runs);
    }
} 