<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Di\FactoryDefault;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;

require_once('vendor/autoload.php');

$loader = new Loader();

$loader->registerNamespaces(
    [
        'MyApp\Controllers' => './app/controllers/',
        'App\Listeners'     =>  './app/listener/'
    ]
);
$loader->registerDirs([
    "./app/models",
]);
$loader->register();

$container = new FactoryDefault();

$manager = new Manager();

$app = new Micro();

/**
 * before request handle event
 */
$manager->attach(
    'micro',
    new App\Listeners\NotificationsListener()
);

$app->before(
    new App\Listeners\NotificationsListener()
);

/**
 * authentication micro controller for token generation
 */
$authentication = new MicroCollection($container);
$authentication
    ->setHandler(new MyApp\Controllers\AuthenticationController())
    ->setPrefix('/authenticate')
    ->get('/', 'index')
    ->get('/token/{name:}&{role:}', 'generateToken');

$app->mount($authentication);

/**
 * product micro controller
 */
$product = new MicroCollection($container);
$product
    ->setHandler(new MyApp\Controllers\ProductController())
    ->setPrefix('/product')
    ->get('/', 'index')
    ->get('/get', 'getAllProducts')
    ->get('/get/{limit:[0-9]+}/{page:[0-9]+}', 'getProductsByPage')
    ->get('/get/{name}', 'getProductByName');


$app->mount($product);

// $app->get(
//     '/api/robots/search/{name}',
//     function ($name) {
//     }
// );

// $app->get(
//     '/api/robots/{id:[0-9]+}',
//     function ($id) {
//     }
// );


$app->notFound(
    function () use ($app) {
        $message = 'URL you used in your request doesn’t exist on the server.';
        $app
            ->response
            ->setStatusCode(404, 'Not Found')
            ->sendHeaders()
            ->setContent($message)
            ->send();
    }
);
$container->set(
    'datetime',
    function () {
        return new DateTimeImmutable();
    },
    true
);
$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => 'password123'));
        return $mongo->store;
    },
    true
);
$app->setEventsManager($manager);
try {
    // Handle the request
    $response = $app->handle($_SERVER["REQUEST_URI"]);
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
// $app->handle(
//     $_SERVER["REQUEST_URI"]
// );
