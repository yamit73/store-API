<?php
namespace Api\Handlers;
use Phalcon\Http\Response;
use Users;

/**
 * Class to handle the product related API call
 */
class User
{
    /**
     * Function
     * To get all the product with limit and page
     *
     * @param integer $limit
     * @param integer $page
     * @return array json
     */
    function create()
    {   
        $response=new Response();
        $collection=new Users();
        $collection->add($this->di->get('request')->getPost());
        $response->setStatusCode(200)
                 ->setContent("Registered")
                 ->send();
    }
}