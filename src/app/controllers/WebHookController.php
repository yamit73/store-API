<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class WebHookController extends Controller
{
    /**
     * initializing neccessary objects
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->helper = new \App\Components\Helper();
        $this->collection = new WebHooks();
    }
    /**
     * Add hooks to database
     *
     * @return void
     */
    public function indexAction(): void
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
