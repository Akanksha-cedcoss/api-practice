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
     * get all Orders from db
     *
     * @return void
     */
    public function getAllOrders()
    {
        return $this->collection->find();
    }
    public function addNewOrder($order)
    {
        $status = $this->collection->insertOne($order);
        return $status;
    }
    public function updateOrderStatus($order_id, $status)
    {
        $status = $this->collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($order_id)], ['$set' =>['status'=>$status]]);
        return $status;
    }
}