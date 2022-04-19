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
            $this->collection=$this->di->get('mongo')->products;
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
        public function getProducts($limit,$page)
        {
            return $this->collection->find([],['limit'=>$limit, 'skip'=>($limit*($page-1))]);
        }
    
        /**
         * Search a product with name
         *
         * @param [string] $product, product name
         * @return array
         */
        public function searchProduct($product)
        {
            return $this->collection->find(['name' => $product]);
        }

}
