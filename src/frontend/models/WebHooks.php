<?php

use Phalcon\Mvc\Model;

class WebHooks extends Model
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
            $this->collection=$this->di->get('mongoBackend')->hooks;
        }
    
        /**
         * Add user to collection
         *
         * @param [array] $user
         * takes user information as array
         * @return void
         */
        public function add($hook)
        {
            return $this->collection->insertOne($hook)->getInsertedId();
        }

        public function findHook($user)
        {
            return $this->collection->findOne(['$and'=>[$user]]);
        }
}
