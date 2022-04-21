<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Order;

/**
 * @property Response $response
 */
class OrderController extends Controller
{
    /**
     * get all products
     *
     * @return void
     */
    public function addNewOrder()
    {
        $order = new Order;
        $key = "example_key";
        $jwt = JWT::decode($this->request->getQuery('bearer'), new Key($key, 'HS256'));
        $role = $jwt->sub;
        $name = $jwt->nam;
        $newOrder = $this->request->getJsonRawBody();
        $newOrder->User_name = $name;
        $newOrder->status = 'paid';
        $reqResult = $order->addNewOrder($newOrder);
        if ($reqResult) {
            $content = [
                "success" => true,
                "payload" => [
                    'Request sent by' =>$name,
                    'Role of User' => $role,
                    "message" => "Order placed Successfully."
                ],
            ];
        } else {
            $content = [
                "success" => false,
                "payload" => "Order Can not be placed Successfully.",
                "errors" =>
                [
                    "ERROR:" . $reqResult . "",
                ]
            ];
        }
        $this->response->setStatusCode(200)->setJsonContent($content);
        return $this->response;
    }
    public function updateOrderStatus()
    {
        $order = new Order;
        $key = "example_key";
        $jwt = JWT::decode($this->request->getQuery('bearer'), new Key($key, 'HS256'));
        $role = $jwt->sub;
        $name = $jwt->nam;
        $body = $this->request->getJsonRawBody();
        $order_id = $body->order_id;
        $status = $body->status;
        $reqResult = $order->updateOrderStatus($order_id, $status);
        if ($reqResult) {
            $content = [
                "success" => true,
                "payload" => [
                    'Request sent by' =>$name,
                    'Role of User' => $role,
                    "order id" =>$order_id,
                    "message" => "Order status is updated successfully."
                ],
            ];
        } else {
            $content = [
                "success" => false,
                "payload" => "Order status can not updated.",
                "errors" =>
                [
                    "ERROR:" . $reqResult . "",
                ]
            ];
        }
        $this->response->setStatusCode(200)->setJsonContent($content);
        return $this->response;
    }
}
