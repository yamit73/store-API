<?php

use Phalcon\Mvc\Model;
use Firebase\JWT\Key;

class Orders extends Model
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
            $this->collection=$this->di->get('mongo')->orders;
        }
    
        /**
         * Add product to collection
         *
         * @param [array] $product
         * takes product information as array
         * @return void
         */
        public function add($order)
        {
            $this->collection->insertOne($order);
        }
    
        /**
         * To get details of all the products
         * With limit and offset
         *
         * @return void
         */
        public function updateOrder($id,$status)
        {
            return $this->collection->updateOne(['_id'=>new MongoDB\BSON\ObjectID($id)],['$set'=>['status'=>$status]]);
        }

         /**
         * To get details of all the orders
         *
         * @return void
         */
        public function getOrders()
        {
            return $this->collection->find();
        }

}
