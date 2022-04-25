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
    public function createAction()
    {
        if (!$this->helper->userLogin()) {
            $this->response->redirect('/frontend/users/login');
        }
        $token='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NTA4NjI4OTMsIm5iZiI6MTY1MDg2MjgzMywiZXhwIjoxNjUwOTQ5MjkzLCJzdWIiOiJhcGlfdG9rZW4iLCJ1aWQiOiI2MjY2MmIyZDQ1Y2I0MDc1ZjIwMjFhMDIiLCJyb2wiOiJ1c2VyIn0.hhhjiwDQ4tJA-484aUUKbeYnCHIciZ1duR8iNmDqgL8';
        $response = $this->client->request('GET', 'products/get&token='.$token.'');
        $this->view->products = json_decode($response->getBody(), true);

        if ($this->request->isPost()) {
            $data=json_encode($this->request->getPost());
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ];
            $response = $this->client->request('POST', 'orders/create?token='.$token.'', ['headers' => $headers, 'body' => $data]);
        }
    }
}
