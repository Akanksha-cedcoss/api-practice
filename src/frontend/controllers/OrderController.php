<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    public function placeOrderAction($product_id)
    {
        $this->view->product_id = $product_id;
        if ($_POST) {
            $token = $this->di->get('config')->app->token;
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
           
            $client = new Client(['base_uri' => '192.168.2.49:8080/api/']);
            $body = json_encode($this->request->getPost());
            try {
                $result = $client->request('POST', "order/create?bearer=" . $token, ["headers" => $headers, 'body' => json_encode($body)])->getBody();
                // $result = $client->request('POST', "order/create?bearer=" . $token, [
                //     'form_params' => 
                //     $body
                // ])->getBody();
                die('this1 ' . $result);
            } catch (Exception $e) {
                die($e->getMessage());
            }
            die('this ' . $result);
        }
    }
}
