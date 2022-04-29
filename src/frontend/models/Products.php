<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Products extends Model
{
    /**
     * Constructor to initialize the collection
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->products;
    }
    /**
     * Add product to collection
     *
     * @param array $product
     * 
     * @return void
     */
    public function addMany(array $products): void
    {
        $this->collection->insertMany($products);
    }
    /**
     * To get details of all the products
     *
     * @return array
     */
    public function getProducts()
    {
        return $this->collection->find();
    }
    /**
     * Add product
     * 
     * @param array $product
     * 
     * @return void
     */
    public function add(array $product): void
    {
        $this->collection->insertOne($product);
    }
    /**
     * Find product by ID
     *
     * @param string $id
     * 
     * @return array
     */
    public function findProduct(string $id)
    {
        return $this->collection->findOne(
            ['_id' => new MongoDB\BSON\ObjectID($id)]
        );
    }
    /**
     * Update product
     *
     * @param array $data
     * 
     * @return void
     */
    public function updateProduct(array $data): void
    {
        $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($data['updatedId'])],
            ['$set' => $data['field']]
        );
    }
}
