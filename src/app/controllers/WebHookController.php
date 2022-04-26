<?php

use Phalcon\Mvc\Controller;

class WebHookController extends Controller
{
    public $helper;
    public $collection;
    function initialize()
    {
        $this->helper= new \App\Components\Helper();
        $this->collection= new WebHooks();
    }
    /**
     * Add hooks to database
     *
     * @return void
     */
    public function indexAction()
    {
        //Check if User is logged in
        if (!$this->helper->userLogin()) {
            $this->response->redirect('/frontend/users/login');
        }
        if ($this->request->isPost()) {
            $this->collection->add($this->request->getpost());
        }
    }
}
