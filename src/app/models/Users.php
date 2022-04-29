<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Users extends Model
{
    /**
     * Constructor to initialize the collection
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->users;
    }
    /**
     * Add user to collection
     *
     * @param array $user
     * takes user information as array
     * @return string
     */
    public function add(array $user)
    {
        return $this->collection->insertOne($user)->getInsertedId();
    }
    /**
     * Find user by ID Password
     *
     * @param array $user
     * @return array
     */
    public function findUser(array $user)
    {
        return $this->collection->findOne(['$and' => [$user]]);
    }
}
