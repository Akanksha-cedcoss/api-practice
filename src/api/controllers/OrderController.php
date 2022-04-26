<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Order;
use Products;
use Users;

// require_once("App\Listeners\NotificationsListener.php");
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
        $products = new Products;
        $key = "example_key";
        // $jwt = JWT::decode($this->request->getQuery('bearer'), new Key($key, 'HS256'));
        // $role = $jwt->sub;
        // $user_id = $jwt->uid;
        $newOrder = $this->request->getJsonRawBody();
        $product = $products->getProductById($newOrder->product_id);
        if (is_null($product)) {
            $content = [
                "success" => false,
                "payload" => [
                    'Request sent by' => USER_ID,
                    'Role of User' => ROLE,
                    "message" => "Product is not found in the database.",
                    "error" => [
                        "Product id is invalid"
                    ]
                ],
            ];
            return $this->response->setStatusCode(400)->setJsonContent($content);
        }else if ($product->stock<=0) {
            $content = [
                "success" => false,
                "payload" => [
                    'Request sent by' => USER_ID,
                    'Role of User' => ROLE,
                    "message" => "Sorry, product is out of stock right now.",
                    "error" => [
                        "Product stock is 0."
                    ]
                ],
            ];
            return $this->response->setStatusCode(400)->setJsonContent($content);
        }
         else {
            $newOrder->user_id = $GLOBALS['user'];
            $newOrder->status = 'paid';
            $reqResult = $order->addNewOrder($newOrder);
            if ($reqResult) {
                $content = [
                    "success" => true,
                    "payload" => [
                        'Request sent by' => USER_ID,
                        'Role of User' => ROLE,
                        "message" => "Order placed Successfully.",
                        "Order created" => $newOrder
                    ],
                ];
                return $this->response->setStatusCode(200)->setJsonContent($content);
            } else {
                $content = [
                    "success" => false,
                    "payload" => "Order Can not be placed Successfully.",
                    "errors" =>
                    [
                        "ERROR:" . $reqResult . "",
                    ]
                ];
                return $this->response->setStatusCode(500)->setJsonContent($content);
            }
        }
    }
    public function updateOrderStatus()
    {
        $order = new Order;
        $key = "example_key";
        // $jwt = JWT::decode($this->request->getQuery('bearer'), new Key($key, 'HS256'));
        // $role = $jwt->sub;
        // $name = $jwt->nam;
        $body = $this->request->getJsonRawBody();
        $order_id = $body->order_id;
        $status = $body->status;
        $reqResult = $order->updateOrderStatus($order_id, $status);
        if ($reqResult) {
            $content = [
                "success" => true,
                "payload" => [
                    'Request sent by' => USER_ID,
                    'Role of User' => ROLE,
                    "order id" => $order_id,
                    "message" => "Order status is updated successfully."
                ],
            ];
            $this->response->setStatusCode(200)->setJsonContent($content);
            return $this->response;
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
        return $this->response->setStatusCode(500)->setJsonContent($content);
    }
}
