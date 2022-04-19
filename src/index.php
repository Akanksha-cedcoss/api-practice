<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Di\FactoryDefault;

$loader = new Loader();

$loader->registerNamespaces(
    [
        'MyApp\Models' => './models',
        'MyApp\Controllers' => './controllers'
    ]
);
$loader->register();

$container = new FactoryDefault();

$robot = new \MyApp\Models\Products;
$app = new Micro();
$app->get(
    '/products/search',
    [
        $robot,
        //function name
        'get'
    ]
);
$invoices = new MicroCollection($container);
$invoices
    ->setHandler(new MyApp\Controllers\InvoicesController())
    ->setPrefix('/invoices')
    ->get('/', 'index')
    ->get('/view/{id}', 'view')
;

$app->mount($invoices);
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

// $app->post(
//     '/api/robots',
//     function () {
//     }
// );

// $app->put(
//     '/api/robots/{id:[0-9]+}',
//     function ($id) {
//     }
// );

// $app->delete(
//     '/api/robots/{id:[0-9]+}',
//     function ($id) {
//     }
// );

try {
    // Handle the request
    $response = $app->handle( $_SERVER["REQUEST_URI"]);
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
// $app->handle(
//     $_SERVER["REQUEST_URI"]
// );
