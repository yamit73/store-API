<?php

declare(strict_types=1);

namespace Frontend\Components;

use Phalcon\Di\Injectable;

final class Helper extends Injectable
{
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function userLogin()
    {
        //Check if User is logged in
        if (! isset($this->frontendSession->userId)) {
            return false;
        }
        return true;
    }
}
