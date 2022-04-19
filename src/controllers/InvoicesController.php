<?php
namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

/**
 * @property Response $response
 */
class InvoicesController extends Controller
{
    public function index()
    {
        // ...
    }

    public function view($id)
    {
        // $content = "<h1>Invoice #{$id}!</h1>";
        $content = [
            'data'=>'welcome'
        ];
        $this->response->setStatusCode(404, 'Not Found')->setJsonContent($content);

        return $this->response;
    }
}
