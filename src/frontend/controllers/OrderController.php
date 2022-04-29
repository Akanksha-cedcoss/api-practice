<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Phalcon\Mvc\Controller;

final class OrderController extends Controller
{
    /**
     * Place order for product by using API
     *
     * @param int $product_id
     * @return void
     */
    public function placeOrderAction(int $product_id): void
    {
        $this->view->product_id = $product_id;
        if ($this->request->getPost()) {
            $token = $this->di->get('config')->app->token;
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            $client = new Client(['base_uri' => '192.168.2.49:8080/api/']);
            $body = json_encode($this->request->getPost());
            try {
                $result = $client->request('POST', 'order/create?bearer=' . $token, ['headers' => $headers, 'body' => json_encode($body)])->getBody();
                $this->flash->setContent($result);
            } catch (Exception $e) {
                $this->flash->setContent($e->getMessage());
            }
        }
    }
}
