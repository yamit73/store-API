<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class WebHooks extends Model
{
    /**
     * Constructor to initialize the collection
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->hooks;
    }
    /**
     * Add hooks to database
     *
     * @param array $hook
     * @return string
     */
    public function add(array $hook)
    {
        return $this->collection->insertOne($hook)->getInsertedId();
    }
    /**
     * get hook by evens name
     *
     * @param string $event
     * @return array
     */
    public function getHooks(string $event)
    {
        return $this->collection->find(
            [
                'events' => [
                    '$eq' => $event,
                ],
            ]
        );
    }
}
