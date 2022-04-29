<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

class Orders extends Model
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
     * Update orders
     *
     * @param string $id
     * @param string $status
     * 
     * @return void
     */
    public function updateOrder($id, $status)
    {
        $this->collection->updateOne(
            [
                '_id' => new MongoDB\BSON\ObjectID($id),
            ],
            [
                '$set' => ['status' => $status],
            ],
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
