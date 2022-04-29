<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;
// use GuzzleHttp\Exception\ClientException;

final class ProductsController extends Controller
{
    /**
     * Initalizing objects when creating a constructor of this class
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->helper = new \Frontend\Components\Helper();
        $this->client = $this->di->get('clients');
        $this->collection = new Products();
    }
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function indexAction(): void
    {
        $this->view->products = $this->collection->getProducts();
    }
    /**
     * Get all products from API and add it to the database
     *Uncomment below function
     *to add all the products in your DB for the first time
     * @return void
     */
    /*
    public function addAction()
    {
        try {
            $token=$this->di->get('config')->get('api')->get('token');
            $response = $this->client->request(
                'GET',
                'products/get&token='.$token.''
            );
            $products = json_decode($response->getBody(),true);
            foreach($products as $key => $product) {
                $products[$key]['_id'] = new \MongoDB\BSON\ObjectId(
                    $product['_id']['$oid']
                );
            }
            $this->collection->addMany($products);
        } catch (ClientException $e) {
            die($e->getMessage());
        }
    }
    */
}
