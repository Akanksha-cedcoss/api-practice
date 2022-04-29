<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Webhooks extends Model
{
    /**
     * initializing mongo constructor
     *
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->webhooks;
    }
    /**
     * add new webhook in the db
     *
     * @param array $webhook
     * 
     */
    public function addNewWebhook(array $webhook):void
    {
        $this->collection->insertOne($webhook);
    }
    /**
     * get webhook by event parameter
     *
     * @param string $event
     * 
     * @return array
     */
    public function getWebhookByEvent(string $event):array
    {
        return $this->collection->find(['event' => $event]);
    }
}