<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Phalcon\Config\ConfigFactory;
use Phalcon\Debug;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Manager;
use Phalcon\Url;

(new Debug())->listen();
$container = new FactoryDefault();
// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/frontend');
$_SERVER['REQUEST_URI'] = str_replace(
    '/frontend/',
    '/',
    $_SERVER['REQUEST_URI']
);
//Vendor file
require_once './vendor/autoload.php';
//Loader-----start----
$loader = new Loader();
/**
 * Register namespace
 */
$loader->registerNamespaces(
    [
        'Frontend\Components' => './components',
    ]
);
/**
 * Registering controllers and models dir
 */
$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);
$loader->register();
//loader-----end-------
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
 * Components Di
 */
$container->setShared(
    'components',
    function () {
        return new \Frontend\Components\Helper();
    }
);
/**
 * Container for config
 * Contains neccessory variables
 */
$container->set(
    'config',
    function () {
        $file = './config/config.php';
        $factory = new ConfigFactory();
        return $factory->newInstance('php', $file);
    }
);
/**
 * Mongo DB container
 */
$container->set(
    'mongo',
    function () {
        $config = $this->get('config')->db;
        $mongo = new \MongoDB\Client(
            'mongodb://mongo',
            [
                'username' => $config->username,
                'password' => $config->password,
            ]
        );
        return $mongo->store_api_frontend;
    },
    true
);
//Di container for guzzle client
$container->set(
    'clients',
    function () {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => '192.168.2.50:8080/api/',
        ]);
    },
    true
);
/**
 * Di container
 * Session
 * Shared
 */
$container->setShared(
    'frontendSession',
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
        $_SERVER['REQUEST_URI']
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
