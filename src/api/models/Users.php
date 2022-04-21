<?php

use Phalcon\Mvc\Model;

class Users extends Model
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
            $this->collection=$this->di->get('mongo')->users;
        }
    
        /**
         * Add user to collection
         *
         * @param [array] $user
         * takes user information as array
         * @return void
         */
        public function add($user)
        {
            $this->collection->insertOne($user);
        }
}
