<?php

use Phalcon\Mvc\Controller;
use Firebase\JWT\JWT;

class UsersController extends Controller
{
    /**
     * Constructor to initialize the object of users model
     *
     * @var [object]
     */
    public $collection;
    public $helper;
    public function initialize()
    {
        $this->collection = new Users();
        $this->helper = new \Frontend\Components\Helper();
    }
    /**
     * user login function
     * User can only login if he is admin
     *
     * @return void
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $escaper=new \Frontend\Components\MyEscaper();
            $user=$escaper->sanitize($this->request->getpost());
            $user=$this->collection->findUser($user);
            $this->frontendSession->set('userId', $user->_id);
            $this->frontendSession->set('userName', $user->name);
            $this->frontendSession->set('userRole', $user->role);
            $this->response->redirect('/frontend/products');
        }
    }

    /**
     * User registration
     * token generate
     *
     * @return void
     */
    public function signupAction()
    {
        if ($this->request->isPost()) {
            $escaper=new \Frontend\Components\MyEscaper();
            $user=$escaper->sanitize($this->request->getpost());
            $user['role']='user';
            $userId=$this->collection->add($user);
            if (isset($userId)) {
                $this->view->token = $this->helper->getToken((string)$userId);
            } else {
                $this->view->token= "Something went wrong user not created!";
            }
            
        }
    }
    /**
     * Logout function
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->frontendSession->destroy();
        $this->response->redirect('/frontend/users/login');
    }
}
