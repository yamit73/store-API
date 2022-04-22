<?php

use Phalcon\Mvc\Model;

class Products extends Model
{
    //Collection
    public $collection;
    /**
     * Constructor to initialize the collection
     *
     * @return void
     */
    public function initialize()
    {
        $this->collection = $this->di->get('mongo')->products;
    }

    /**
     * Add product to collection
     *
     * @param [array] $product
     * takes product information as array
     * @return void
     */
    public function add($product)
    {
        $this->collection->insertOne($product);
    }

    /**
     * To get details of all the products
     *
     * @return void
     */
    public function getProducts()
    {
        return $this->collection->find();
    }

    /**
     * Search keywords and if matched return that document
     *
     * @param [array] $keys
     * @return array  Products
     */
    public function searchProduct($keys)
    {
        return $this->collection->find(['$or' => $keys]);
    }

    public function findProduct($id)
    {
        return $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);
    }
    
    /**
     * Add product to collection
     *
     * @param [array] $product
     * takes product information as array
     * @return void
     */
    public function updateProduct($product, $id)
    {
        $this->collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($id)], ['$set' => $product]);
    }
}
