<?php

declare(strict_types=1);

use Phalcon\Config\ConfigFactory;
use Phalcon\Debug;
use Phalcon\Di\FactoryDefault;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Manager;
use Phalcon\Url;
(new Debug())->listen();
// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/frontend');
$_SERVER['REQUEST_URI'] = str_replace('/frontend/', '/', $_SERVER['REQUEST_URI']);
require_once BASE_PATH . '/library/vendor/autoload.php';

// Register an autoloader
$loader = new Loader();
$loader->registerNamespaces(
    [
        'App\components' => './components/',
    ]
);
$loader->registerDirs([
    './models',
]);
$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);

$loader->register();

$container = new FactoryDefault();

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
/**
 *register session service
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
 *register config file
 */
$container->set(
    'config',
    function () {
        $file_name = './components/config.php';
        $factory = new ConfigFactory();
        return $factory->newInstance('php', $file_name);
    }
);
/**
 *register db service using config file
 */
$container->set(
    'mongo',
    function () {
        $mango = $this->get('config')->mongo;
        $mongo = new \MongoDB\Client('mongodb://mongo', array(
            'username' => $mango->username,
            'password' => $mango->password
        ));
        return $mongo->store;
    },
    true
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