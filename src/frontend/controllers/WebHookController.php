<?php

use Phalcon\Mvc\Controller;

class WebHookController extends Controller
{
    public $helper;
    public $collection;
    function initialize()
    {
        $this->helper= new \Frontend\Components\Helper();
        $this->collection= new WebHooks();
    }
    public function indexAction()
    {
        //Check if User is logged in
        if (!$this->helper->userLogin()) {
            $this->response->redirect('/frontend/users/login');
        }
        if ($this->request->isPost()) {
            $escaper=new \Frontend\Components\MyEscaper();
            $data=$escaper->sanitize($this->request->getpost());
            $this->collection->add($data);
            return "Success";
        }
    }
}
