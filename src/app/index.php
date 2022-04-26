<?php

use Phalcon\Di\FactoryDefault;
//Required class for loader
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Events\Manager as EventsManager;
use GuzzleHttp\Client;
use Phalcon\Url;
use \Phalcon\Debug;
/**
 * Required classes for session
 */
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;

$config = new Config([]);

(new Debug())->listen();

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
$_SERVER['REQUEST_URI'] = str_replace("/app/", "/", $_SERVER['REQUEST_URI']);

//Vendor file
require_once("./vendor/autoload.php");

//Loader-----start----
$loader = new Loader();
$container = new FactoryDefault();

/**
 * Register namespace
 */
$loader->registerNamespaces(
    [
        'App\Components'=>'./components',
        'App\Events'=>'./events'
    ]
);
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

/**
 * Container for config 
 * Contains neccessory variables
 */
$container->set(
    'config',
    function () {
        $file='./config/config.php';
        $factory=new ConfigFactory();
        return $factory->newInstance('php', $file);
    }
);

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
        $config=$this->get('config')->db;
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => $config->username, "password" => $config->password));
        return $mongo->store_api;
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

//Di container for guzzle client
$container->set(
    'appClient',
    function() {
        $client = new Client([
            // Base URI is used with relative requests
        ]);
        return $client;
    },
    true
);
//Creating object of application class
$application = new Application($container);

//Event manager
$eventsManager=new EventsManager();
$eventsManager->attach(
    'event',
    new \App\Events\EventListener()
);
$application->setEventsManager($eventsManager);
$container->set(
    'EventsManager',
    $eventsManager
);

$container->setShared(
    'components',
    function () {
        return new \App\Components\Helper();
    }
);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
