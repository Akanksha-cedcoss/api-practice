<?php

declare(strict_types=1);

namespace App\Events;

use GuzzleHttp\Client;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Webhooks;

/**
 * event listener class for webhooks
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
    ): void {
        $webhook = new Webhooks();
        /**
         * get all wenhooks for new product update from Webhook collection
         */
        $hook = $webhook->getWebhookByEvent('Product.add');
        /**
         * creating product array from product onject
         */
        $product = json_decode(json_encode($product), true);
        /**
         * initializing default product id as objectID
         */
        $product['_id'] = new \MongoDB\BSON\ObjectID($product['_id']['$oid']);
        if (!is_null($hook)) {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            /**
             * sending request to each url for new product update
             */
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
    ): void {
        $webhook = new Webhooks();
        /**
         * get all webhooks for product stock update
         */
        $hook = $webhook->getWebhookByEvent('Product.stock');
        if (!is_null($hook)) {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            /**
             * sending request to each url
             */
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
    ): void {
        $webhook = new Webhooks();
        /**
         * getting all webhooks for product price update
         */
        $hook = $webhook->getWebhookByEvent('Product.price');
        if (!is_null($hook)) {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            /**
             * sending request to each url
             */
            foreach ($hook as $huk) {
                $client = new Client(['base_uri' => $huk->url]);
                $client->request('POST', "", ["headers" => $headers, 'body' => json_encode($data)]);
            }
        }
    }
}
