<?php

declare(strict_types=1);

use Firebase\JWT\JWT;
use Phalcon\Mvc\Controller;

final class UsersController extends Controller
{
    public function initialize(): void
    {
        $this->collection = new Users();
    }
    /**
     * user login function
     * User can only login if he is admin
     *
     * @return void
     */
    public function loginAction(): void
    {
        if ($this->request->isPost()) {
            $user = $this->collection->findUser($this->request->getPost());
            $this->session->set('userId', $user->_id);
            $this->session->set('userName', $user->name);
            $this->session->set('userRole', $user->role);
            $this->response->redirect('/app/orders');
        }
    }
    /**
     * User registration
     * token generate
     *
     * @return string
     */
    public function signupAction()
    {
        if ($this->request->isPost()) {
            $user = $this->request->getPost();
            $user['role'] = 'user';
            $userId = $this->collection->add($user);
            
            $key = 'example_key';
            $now = new \DateTimeImmutable();
            $payload = array(
                'iat' => $now->getTimestamp(),
                'nbf' => $now->modify('-1 minute')->getTimestamp(),
                'exp' => $now->modify('+1 days')->getTimestamp(),
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
    public function logoutAction(): void
    {
        $this->session->destroy();
        $this->response->redirect('/app/users/login');
    }
}
