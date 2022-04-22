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
    public function initialize()
    {
        $this->collection = new Users();
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
            $user=$this->collection->findUser($this->request->getPost());
            $this->session->set('userId', $user->_id);
            $this->session->set('userName', $user->name);
            $this->session->set('userRole', $user->role);
            $this->response->redirect('/app/admin/orders');
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
            $user=$this->request->getPost();
            $user['role']='user';
            $userId=$this->collection->add($user);
            // die($userId);
            $key = "example_key";
            $now = new \DateTimeImmutable();
            $payload = array(
                "iat" => $now->getTimestamp(),
                "nbf" => $now->modify('-1 minute')->getTimestamp(),
                "exp" => $now->modify('+1 days')->getTimestamp(),
                'sub' => 'api_token',
                'uid' => (string)$userId,
                'rol' => 'user',
            );
            $token = JWT::encode($payload, $key, 'HS256');
            $this->view->token = $token;
        }
    }
    /**
     * Logout function
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect('/app/users/login');
    }
}
