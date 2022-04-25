<?php

use Phalcon\Mvc\Controller;
class OrderController extends Controller
{
    public $helper;
    public $collection;
    function initialize()
    {
        $this->helper= new \App\Components\Helper();
        $this->collection=new Orders;
    }
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function indexAction()
    {
        // echo $helper->userLogin();die;
        if (!$this->helper->userLogin()) {
            $this->response->redirect('/app/users/login');
        }
        $this->view->orders=$this->collection->getOrders();
    }
}
