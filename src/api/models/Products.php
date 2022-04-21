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
    /**
     * get product by name from db
     *
     * @param [type] $name
     * @return object
     */
    public function getProductByName($parameter)
    {
        $name = [];
        foreach ($parameter as $param) {
            array_push($name, array("name" =>  new \MongoDB\BSON\Regex($param, 'i')));
        }
        return $this->collection->find(['$or' => $name]);
    }
    public function getProductsByLimit($limit, $page)
    {
        return $this->collection->find([], ['limit' => $limit, 'skip' => ($limit * ($page - 1))]);
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
}
