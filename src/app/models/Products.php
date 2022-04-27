<?php

use Phalcon\Mvc\Model;

class Products extends Model
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
    public function getProductById($product_id)
    {
        return $this->collection->findOne(['_id' => new \MongoDB\BSON\ObjectID($product_id)]);
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
        return $this->collection->insertOne($product)->getInsertedId();
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
