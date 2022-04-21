<?php

use Phalcon\Di\FactoryDefault;
//Required class for loader
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
/**
 * Required classes for session
 */
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
$_SERVER['REQUEST_URI'] = str_replace("/app/", "/", $_SERVER['REQUEST_URI']);

//Vendor file
require_once("./vendor/autoload.php");

//Loader-----start----
$loader = new Loader();
/**
 * Registering controllers and models dir
 */
$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);
$loader->register();
//loader-----end-------

$container = new FactoryDefault();
/**
 * Di container for view
 */
$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);
/**
 * Url container
 */
$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
/**
 * Mongo DB container
 */

$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => 'password123'));

        return $mongo->store;
    },
    true
);
/**
 * Di container
 * Session
 * Shared
 */
$container->setShared(
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
    }
);
//Creating object of application class
$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
