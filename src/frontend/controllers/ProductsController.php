<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Exception\ClientException;

class ProductsController extends Controller
{
    /**
     * Initalizing objects when creating a constructor of this class
     *
     * @var [type]
     */
    public $helper;
    public $client;
    public $collection;
    function initialize()
    {
        $this->helper= new \Frontend\Components\Helper();
        $this->client=$this->di->get('clients');
        $this->collection=new Products;
    }
    
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function indexAction()
    {
       $this->view->products=$this->collection->getProducts();
    }

    /**
     * Get all products from API and add it to the database
     *
     * @return void
     */
    public function addAction()
    {
        try {
            $token='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NTA4NjI4OTMsIm5iZiI6MTY1MDg2MjgzMywiZXhwIjoxNjUwOTQ5MjkzLCJzdWIiOiJhcGlfdG9rZW4iLCJ1aWQiOiI2MjY2MmIyZDQ1Y2I0MDc1ZjIwMjFhMDIiLCJyb2wiOiJ1c2VyIn0.hhhjiwDQ4tJA-484aUUKbeYnCHIciZ1duR8iNmDqgL8';
            $response = $this->client->request('GET', 'products/get&token='.$token.'');
            $products=json_decode($response->getBody(),true);
            foreach($products as $key => $product) {
                $products[$key]['_id']=new \MongoDB\BSON\ObjectId($product['_id']['$oid']);
            }
            $this->collection->addMany($products);
        } catch (ClientException $e) {
            die($e->getMessage());
        }
    }
}
