<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Orders extends Model
{
    /**
     * Constructor to initialize the collection
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->orders;
    }
    /**
     * To get details of all the products
     * With limit and offset
     *
     * @return array
     */
    public function updateOrder(string $id, string $status)
    {
        return $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($id)],
            ['$set' => ['status' => $status]]
        );
    }
    /**
     * To get details of all the orders
     *
     * @return array
     */
    public function getOrders()
    {
        return $this->collection->find();
    }
}
