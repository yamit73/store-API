<?php

use Phalcon\Mvc\Controller;

class AdminController extends Controller
{
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function ordersAction()
    {
        if (!isset($this->session->userId)) {
            $this->response->redirect('/app/users/login');
        }
        $order= new Orders();
        $this->view->orders=$order->getOrders();
    }
}
