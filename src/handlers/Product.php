<?php
namespace Api\Handlers;

use Products;

/**
 * Class to handle the product related API call
 */
class Product
{
    /**
     * Function
     * To get all the product with limit and page
     *
     * @param integer $limit
     * @param integer $page
     * @return array json
     */
    function getProducts($limit=10,$page=1)
    {
     $collection=new Products();
     $result=$collection->getProducts((int)$limit,(int)$page);
     $products=[];
     foreach ($result as $key=>$val) {
        $products[$key]=json_decode(json_encode($val),true);
     } 
    return json_encode($products);
    }
}