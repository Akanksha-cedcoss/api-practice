<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Url;
use GuzzleHttp\Client;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
$_SERVER['REQUEST_URI'] = str_replace("/app/", "/", $_SERVER['REQUEST_URI']);
require_once(APP_PATH . '/vendor/autoload.php');
// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);


$loader->register();

$container = new FactoryDefault();
$loader->registerNamespaces(
    [
        'App\Events' => APP_PATH . '/events',
    ]
);
$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($container);
// Register the flash service with custom CSS classes
$container->set(
    'flash',
    function () {
        return new FlashDirect();
    }
);
$container->set(
    'client',
    function () {
        $client = new Client([
            'base_uri' => 'https://api.spotify.com/v1/'
        ]);
        return $client;
    }
);
// Register Event manager
$eventsManager = new EventsManager();
$eventsManager->attach(
    'webhooks',
    new App\Events\Webhook
);
$application->setEventsManager($eventsManager);

$container->set(
    'EventsManager',
    $eventsManager
);
/**
 * register session service
 */
$container->setShared('session', function () {
    $session = new Manager();
    $files = new Stream([
        'savePath' => '/tmp',
    ]);
    $session->setAdapter($files)->start();
    return $session;
});
/**
 * register db service using config file
 */
$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => 'password123'));
        return $mongo->store;
    },
    true
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
