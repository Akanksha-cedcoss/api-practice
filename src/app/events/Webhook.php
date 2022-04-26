<?php

namespace App\Events;

use Exception as GlobalException;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Phalcon\Exception;
use Webhooks;


/**
 * event listener class
 */
class Webhook extends Injectable
{
    /**
     * get current user
     *
     * @param Event $event
     * @param [type] $component
     * @return void
     */
    public function newProductWebhook(
        Event $event,
        $component,
        $product
    ) {
        $webhook = new Webhooks;
        $hook = $webhook->getWebhookByEvent('Product.add');
        if (!is_null($hook)) {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            foreach ($hook as $huk) {
                $client = new Client(['base_uri' => $huk->url]);
                $client->request('POST', "", ["headers" => $headers, 'body' => json_encode($product)]);
            }
        }
    }
    /**
     * update product stock
     *
     * @param Event $event
     * @param [type] $component
     * @return void
     */
    public function updateStockWebhook(
        Event $event,
        $component,
        $data
    ) {
        $webhook = new Webhooks;
        $hook = $webhook->getWebhookByEvent('Product.stock');
        if (!is_null($hook)) {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            foreach ($hook as $huk) {
                $client = new Client(['base_uri' => $huk->url]);
                $client->request('POST', "", ["headers" => $headers, 'body' => json_encode($data)]);
            }
        }
    }
    /**
     * update product stock
     *
     * @param Event $event
     * @param [type] $component
     * @return void
     */
    public function updatePriceWebhook(
        Event $event,
        $component,
        $data
    ) {
        $webhook = new Webhooks;
        $hook = $webhook->getWebhookByEvent('Product.price');
        if (!is_null($hook)) {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            foreach ($hook as $huk) {
                $client = new Client(['base_uri' => $huk->url]);
                $client->request('POST', "", ["headers" => $headers, 'body' => json_encode($data)]);
            }
        }
    }
}
