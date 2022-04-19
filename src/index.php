<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Loader;

$di=new FactoryDefault();
//Loader-----start----
$loader=new Loader();
require_once("./vendor/autoload.php");
$loader->registerNamespaces(
    [
        'Api\Handlers'=>'./handlers'
    ]
);
$loader->registerDirs(
    [
       "./models"
    ]
);

$loader->register();
//loader-----end-------

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
$prod=new \Api\Handlers\Product();

$app=new Micro();

/**
 * End point to get all the products 
 * request should consist limit, page
 */
$app->get(
    '/products/get/{limit}/{page}',
    [
        $prod,
        'getProducts'
    ]
);

/**
 * End point to get api_access token
 */
$app->get(
    '/get/api_token',
    [
        new \Api\Handlers\Token(),
        'getToken'
    ]
);
$app->handle($_SERVER["REQUEST_URI"]);