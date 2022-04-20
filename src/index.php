<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Events\Event;
use Phalcon\Mvc\Application;
use Phalcon\Events\Manager as EventsManager;

$app=new Micro();
//Vendor file
require_once("./vendor/autoload.php");

//Loader-----start----
$loader=new Loader();
$di=new FactoryDefault();
$application = new Application($di);
$loader->registerNamespaces(
    [
        'Api\Handlers'=>'./handlers',
        'Api\Events'=>'./events',
    ]
);
$loader->registerDirs(
    [
       "./models"
    ]
);

$loader->register();
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
 * End point to get all the products 
 * if request consist limit, page no
 */
$app->get(
    '/products/get/{limit}/{page}',
    [
        new \Api\Handlers\Product(),
        'getProductsWithLimit'
    ]
);

/**
 * End point to get all the products 
 */
$app->get(
    '/products/get&{token}',
    [
        new \Api\Handlers\Product(),
        'getProducts'
    ]
);

/**
 * End point to search keywords
 */
$app->get(
    '/products/search/{keyword}',
    [
        new \Api\Handlers\Product(),
        'search'
    ]
);
/**
 * End point to get api_access token
 */
$app->get(
    '/get/api_token/{name}',
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