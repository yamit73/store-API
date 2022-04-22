<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
//Vendor file
require_once("./vendor/autoload.php");

//Loader-----start----
$loader=new Loader();
$di=new FactoryDefault();

$loader->registerNamespaces(
    [
        'Api\Handlers'=>'./handlers',
        'Api\Events'=>'./events',
    ]
);
$loader->registerDirs(
    [
       "./models",
       "./handlers"
    ]
);
$loader->register();

$app=new Micro();

//loader-----end-------

//Event ------start---------
$eventsManager=new EventsManager();
$eventsManager->attach(
    'micro',
    new \Api\Events\Authentication()
);
$app->before(
    new \Api\Events\Authentication()
);

/**
 * Mongo DB container
 */

$di->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username"=>'root', "password"=>'password123'));

        return $mongo->store;
    },
    true
);
/**
 * Di container
 * Session
 * Shared
 */
$di->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();
        return $session;
    },
    true
);

$app->setDi($di);
/**
 * End point to get all the products 
 * if request consist limit, page no
 */
$app->get(
    '/api/products/get/{limit}/{page}',
    [
        new \Api\Handlers\Product(),
        'getProductsWithLimit'
    ]
);

/**
 * End point to get all the products 
 */
$app->get(
    '/api/products/get&{token}',
    [
        new \Api\Handlers\Product(),
        'getProducts'
    ]
);

/**
 * End point to search keywords
 */
$app->get(
    '/api/products/search/{keyword}',
    [
        new \Api\Handlers\Product(),
        'search'
    ]
);

/**
 * End point to get all order
 */
$app->get(
    '/api/orders/get',
    [
        new \Api\Handlers\Order(),
        'getOrders'
    ]
);

/**
 * End point to Create order
 */
$app->post(
    '/api/orders/create',
    [
        new \Api\Handlers\Order(),
        'create'
    ]
);

/**
 * End point to update order status
 */
$app->put(
    '/api/orders/update/{orderId}/{status}',
    [
        new \Api\Handlers\Order(),
        'updateOrder'
    ]
);

/**
 * End point to get api_access token
 */
$app->get(
    '/api/get/api_token/{name}',
    [
        new \Api\Handlers\Token(),
        'getToken'
    ]
);

$app->setEventsManager($eventsManager);

try{
    $app->handle($_SERVER["REQUEST_URI"]);
} catch (Exception $e) {
    echo $e->getMessage();
}