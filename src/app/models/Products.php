<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Products extends Model
{
    /**
     * initializing mongo constructor
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->products;
    }
    /**
     * find product by product id
     *
     * @param int $product_id
     * 
     * @return object
     */
    public function getProductById(int $product_id): object
    {
        return $this->collection->findOne(
            ['_id' => new \MongoDB\BSON\ObjectID($product_id)]
        );
    }
    /**
     * get all products from db
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        return $this->collection->find();
    }
    /**
     * add new product to Product Collection
     *
     * @param object $product
     * 
     * @return bool
     */
    public function addNewProduct(int $product): bool
    {
        return $this->collection->insertOne($product)->getInsertedId();
    }
    /**
     * update product stock
     *
     * @param int $product_id
     * @param int $stock
     * 
     * @return void
     */
    public function updateProductStock(int $product_id, int $stock): void
    {
        $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($product_id)],
            ['$set' => ['stock' => $stock]],
        );
    }
    /**
     * update product price
     *
     * @param int $product_id
     * @param int $price
     * 
     * @return void
     */
    public function updateProductPrice(int $product_id, int $price): void
    {
        $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($product_id)],
            ['$set' => ['price' => $price]],
        );
    }
}
