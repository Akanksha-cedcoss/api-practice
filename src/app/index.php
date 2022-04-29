<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Manager;
use Phalcon\Url;

(new \Phalcon\Debug())->listen();
// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));

define('APP_PATH', BASE_PATH . '/app');

$_SERVER['REQUEST_URI'] = str_replace('/app/', '/', $_SERVER['REQUEST_URI']);

require_once BASE_PATH . '/library/vendor/autoload.php';
// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
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
        return new Client([
            'base_uri' => 'https://api.spotify.com/v1/'
        ]);
    }
);
// Register Event manager
$eventsManager = new EventsManager();
$eventsManager->attach(
    'webhooks',
    new App\Events\Webhook()
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
$container->set('mongo', function () {
        $mongo = new \MongoDB\Client(
            'mongodb://mongo',
            [
                'username' => 'root',
                'password' => 'password123'
            ]
        );
        return $mongo->store;
    }, true
);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER['REQUEST_URI']
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
