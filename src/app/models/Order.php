<?php

use Phalcon\Mvc\Model;

class Order extends Model
{
    public $collection;
    /**
     * initializing mongo constructor
     *
     * @return void
     */
    public function initialize()
    {
        $this->collection = $this->di->get("mongo")->order;
    }
    /**
     * get all orders from db
     *
     * @return object
     */
    public function getAllOrders()
    {
        return $this->collection->find();
    }
}
