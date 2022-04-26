<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class ProductController extends Controller
{
    public $products;

    function initialize(){
        $this->products = new Product;
    }

    public function allProductsAction()
    {
        /**
         * view all products
         *
         * @return void
         */
        $this->view->products = $this->products->getAllProducts();
    }
    public function getProductsFromApiAction()
    {
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJhdWQiOiJodHRwOi8vZXhhbXBsZS5jb20iLCJpYXQiOjE2NTA2MDMyMzQsIm5iZiI6MTY1MDYwMzE3NCwiZXhwIjoxNjUwNjg5NjM0LCJ1aWQiOnsiJG9pZCI6IjYyNjEzMWUxMjlmM2M4Y2NkOWY0NmQ5OCJ9LCJzdWIiOiJhZG1pbiJ9.r4UnMr9Ya690bBd9mx7Xu1gn4K2YfAkqFmRrGy87r9Q";
        $client = new Client(['base_uri' => '192.168.2.49:8080/api/']);
        $products = json_decode($client->request('GET', "product/get?bearer=".$token."")->getBody(), true)['payload']['products'];
        foreach($products as $key=>$product) {
            $products[$key]['_id'] = new \MongoDB\BSON\ObjectID($products[$key]['id']['$oid']);
        }
        $this->products->addMultipleProducts($products);
    }
}
