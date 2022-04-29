<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class OrderController extends Controller
{
    public function initialize(): void
    {
        $this->helper = new \App\Components\Helper();
        $this->collection = new Orders();
    }
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function indexAction(): void
    {
        // echo $helper->userLogin();die;
        if (!$this->helper->userLogin()) {
            $this->response->redirect('/app/users/login');
        }
        $this->view->orders = $this->collection->getOrders();
    }
}
