<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Phalcon\Config\ConfigFactory;
use Phalcon\Config;
use Phalcon\Session\Manager as sessionManager;
use Phalcon\Session\Adapter\Stream;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/api');
require_once(APP_PATH . '/vendor/autoload.php');

$loader = new Loader();

$loader->registerNamespaces(
    [
        'MyApp\Controllers' => "./controllers/",
        'App\Listeners'     =>  './listener/',
        'App\components'    =>   './components/'
    ]
);
$loader->registerDirs([
    "./models",
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
    ->setPrefix('/api/authenticate')
    ->get('/', 'index')
    ->get('/token/{name:}', 'generateToken');

$app->mount($authentication);

/**
 * product micro controller
 */
$product = new MicroCollection($container);
$product
    ->setHandler(new MyApp\Controllers\ProductController())
    ->setPrefix('/api/product')
    ->get('/', 'index')
    ->get('/get', 'getAllProducts')
    ->get('/get/{limit:[0-9]+}/{page:[0-9]+}', 'getProductsByPage')
    ->get('/get/{name}', 'getProductByName');

$app->mount($product);
/**
 * order micro controller
 */
$order = new MicroCollection($container);
$order
    ->setHandler(new MyApp\Controllers\OrderController())
    ->setPrefix('/api/order')
    ->get('/', 'index')
    ->put('/update', 'updateOrderStatus')
    ->post('/create', 'addNewOrder');


$app->mount($order);


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
/**
 * register config file
 */
$container->set(
    'config',
    function () {
        $file_name = './components/config.php';
        $factory  = new ConfigFactory();
        return $factory->newInstance('php', $file_name);
    }
);
$container->set(
    'mongo',
    function () {
        $mango = $this->get('config')->mongo;
        $mongo = new \MongoDB\Client("mongodb://mongo", array(
            "username" => $mango->username,
            "password" => $mango->password
        ));
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
