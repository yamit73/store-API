<?php

use Phalcon\Mvc\Controller;
// use GuzzleHttp\Exception\ClientException;

class ProductHookController extends Controller
{
    public $helper;
    public $collection;
    function initialize()
    {
        $this->helper= new \Frontend\Components\Helper();
        $this->collection=new Products;
    }
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $data=json_decode(json_encode($this->request->getJsonRawBody()),true);
            $this->collection->updateProduct($data);
            
        }
    }

    /**
     * Add product to database
     *
     * @return void
     */
    public function addAction()
    {
        if ($this->request->isPost()) {
            $data=json_decode(json_encode($this->request->getJsonRawBody()),true);
            $data['_id']=new \MongoDB\BSON\ObjectId($data['_id']['$oid']);
            $this->collection->add($data);
        }
    }
}
