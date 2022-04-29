<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

class Products extends Model
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
     * @param [array] $product
     * takes product information as array
     * @return void
     */
    public function add($product)
    {
        return $this->collection->insertOne($product)->getInsertedId();
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
     * Search keywords and if matched return that document
     *
     * @param array $keys
     * @return array  Products
     */
    public function searchProduct(array $keys)
    {
        return $this->collection->find(
            [
                '$or' => $keys
            ]
        );
    }
    /**
     * Undocumented function
     *
     * @param string $id
     * @return array
     */
    public function findProduct(string $id)
    {
        return $this->collection->findOne(
            [
                '_id' => new MongoDB\BSON\ObjectID($id)
            ]
        );
    }
    /**
     * Undocumented function
     *
     * @param array $product
     * @param string $id
     * @return int
     */
    public function updateProduct(array $product, string $id)
    {
        return $this->collection->updateOne(
            [
                '_id' => new MongoDB\BSON\ObjectID($id),
            ],
            [
                '$set' => $product,
            ]
        )->getModifiedCount();
    }
}
