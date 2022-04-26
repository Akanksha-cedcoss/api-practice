<?php

use Phalcon\Mvc\Model;

class Product extends Model
{
    public $collection;
    /**
     * initializing mongo constructor
     *
     * @return void
     */
    public function initialize()
    {
        $this->collection = $this->di->get("mongo")->products;
    }
    /**
     * get all products from db
     *
     * @return object
     */
    public function getAllProducts()
    {
        return $this->collection->find();
    }
    public function addNewProduct($product)
    {
        $status = $this->collection->insertOne($product);
        return $status;
    }
    public function addMultipleProducts($products)
    {
        $status = $this->collection->insertMany($products);
        return $status;
    }
    public function updateProductStock($product_id, $stock)
    {
        $this->collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($product_id)], ['$set' =>['stock'=>$stock]]);
    }
    public function updateProductPrice($product_id, $price)
    {
        $this->collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($product_id)], ['$set' =>['price'=>$price]]);
    }
}