<?php

use Phalcon\Mvc\Controller;

class OrdersController extends Controller
{
    public $helper;
    public $client;
    public $collection;
    function initialize()
    {
        $this->helper= new \Frontend\Components\Helper();
        $this->client=$this->di->get('clients');
        $this->collection= new Orders;
    }
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function indexAction()
    {
        if (!$this->helper->userLogin()) {
            $this->response->redirect('/frontend/users/login');
        }
        $order= new Orders();
        $this->view->orders=$order->getOrders();
    }

    /**
     * Function to create order using API request
     *
     * @return void
     */
    public function createAction()
    {
        if (!$this->helper->userLogin()) {
            $this->response->redirect('/frontend/users/login');
        }
        $token=$this->config->get('api')->get('token');
        $response = $this->client->request('GET', 'products/get&token='.$token.'');
        $this->view->products = json_decode($response->getBody(), true);

        if ($this->request->isPost()) {
            $data=json_encode($this->request->getPost());
            $headers = $this->config->get('api')->get('headers');
            $response = $this->client->request('POST', 'orders/create?token='.$token.'', ['headers' => json_decode(json_encode($headers),true), 'body' => $data]);
        }
    }
}
