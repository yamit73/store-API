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
    public function addMany($products)
    {
        $this->collection->insertMany($products);
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
     * Add product
     *
     * @param [array] $product
     * @return void
     */
    public function add($product)
    {
        $this->collection->insertOne($product);
    }

    /**
     * Find product by ID
     *
     * @param [string] $id
     * @return void
     */
    public function findProduct($id)
    {
        return $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);
    }

    /**
     * Update product 
     *
     * @param [array] $data
     * @return void
     */
    public function updateProduct($data)
    {
        $this->collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($data['updatedId'])],['$set'=>$data['field']]);
    }
}
