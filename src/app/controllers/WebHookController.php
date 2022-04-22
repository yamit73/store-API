<?php

use Phalcon\Mvc\Controller;

class WebHookController extends Controller
{
    public function indexAction()
    {
        //Check if User is logged in
        if (!isset($this->session->userId)) {
            $this->response->redirect('/app/users/login');
        }
    }
}
