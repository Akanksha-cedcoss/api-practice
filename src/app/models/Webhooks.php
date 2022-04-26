<?php

use Phalcon\Mvc\Model;

class Webhooks extends Model
{
    public $collection;
    /**
     * initializing mongo constructor
     *
     * @return void
     */
    public function initialize()
    {
        $this->collection = $this->di->get("mongo")->webhooks;
    }
    /**
     * add new webhook in the db
     *
     * @return object
     */
    public function addNewWebhook($webhook)
    {
        $this->collection->insertOne($webhook);
    }
    public function getWebhookByEvent($event)
    {
        return $this->collection->find(['event'=>$event]);
    }
}