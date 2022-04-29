<?php
namespace Api\Handlers;

use Phalcon\Di\Injectable;
use Phalcon\Http\Response;
use Products;

/**
 * Class to handle the product related API call
 */
class Product extends Injectable
{
    /**
     * Function
     * To get all the product with limit and page
     *
     * @param integer $limit
     * @param integer $page
     * @return array json
     */
    public function getProductsWithLimit($limit=5,$page=1)
    {   
        $collection=new Products();
        $result=$collection->getProductsWithlimit((int)$limit,(int)$page);
        $products=[];
        foreach ($result as $key=>$val) {
            $products[$key]=json_decode(json_encode($val),true);
        } 
        $this->response->setStatusCode(200)
                 ->setJsonContent($products)
                 ->send();
    }

    /**
     * To get all the products without any limit
     *
     * @return void
     */
    function getProducts()
    {
        $collection=new Products();
        $result=$collection->getProducts();
        $products=[];
        foreach ($result as $key=>$val) {
            $products[$key]=json_decode(json_encode($val),true);
        } 
        $this->response->setStatusCode(200)
                 ->setJsonContent($products)
                 ->send();
    }

    /**
     * Search keywords in product collection's name column
     *
     * @param [string] $keyword
     * @return array, JSON array
     */
    public function search($keyword)
    {
        $collection=new Products();
        
        //Converting keyword to a proper format to search
        $keys=explode('%20',$keyword);
        $searchKeys=[];
        foreach($keys as $key) {
            array_push($searchKeys, ['name'=> new \MongoDB\BSON\Regex($key, 'i')]);
        }

        //Gettting data from database
        $result=$collection->searchProduct($searchKeys);
        $products=[];
        foreach ($result as $key=>$val) {
            $products[$key]=json_decode(json_encode($val),true);
        } 
        $this->response->setStatusCode(200)
                 ->setJsonContent($products)
                 ->send();
    }
}