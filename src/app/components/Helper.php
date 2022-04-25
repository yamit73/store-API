<?php
namespace App\Components;
use Phalcon\Di\Injectable;

class Helper extends Injectable
{
    public function userLogin()
    {
        //Check if User is logged in
        if (!isset($this->session->userId) || ($this->session->userRole!='admin')) {
            return false;
        }
        return true;
    }
}
