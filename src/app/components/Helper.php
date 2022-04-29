<?php

declare(strict_types=1);

namespace App\Components;

use Phalcon\Di\Injectable;

final class Helper extends Injectable
{
    /**
     * Check if user is logged in or not
     *
     * @return bool
     */
    public function userLogin()
    {
        //Check if User is logged in
        if (! isset($this->session->userId) || ($this->session->userRole !== 'admin')) {
            return false;
        }
        return true;
    }
}
