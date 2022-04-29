<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Product extends Model
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
     * get all products from db
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        return $this->collection->find();
    }
    /**
     * add one new product to the product collection in frontend DB
     *
     * @param array $product
     * 
     */
    public function addNewProduct(array $product): bool
    {
        return $this->collection->insertOne($product);
    }
    /**
     * add multiple products to the product collection in frontend DB
     *
     * @param array $products
     * 
     */
    public function addMultipleProducts(array $products): bool
    {
        return $this->collection->insertMany($products);
    }
    /**
     * update product stock in product collection in frontend DB
     *
     * @param int $product_id
     * 
     * @param int $stock
     */
    public function updateProductStock(int $product_id, int $stock): void
    {
        $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($product_id)],
            ['$set' =>['stock' => $stock]]
        );
    }
    /**
     *update product price in product collection in frontend DB
     *
     * @param int $product_id
     * 
     * @param int $price
     * 
     * @return None
     */
    public function updateProductPrice(int $product_id, int $price): void
    {
        $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($product_id)],
            ['$set' =>['price' => $price]]
        );
    }
}
