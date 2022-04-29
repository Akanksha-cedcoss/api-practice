<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class WebhooksController extends Controller
{
    /**
     * initialize product collection object
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->products = new Product();
    }
    /**
     * handle webhook request of new product update
     *
     * @return void
     */
    public function newProductUpdateAction(): void
    {
        if ($this->request->isPOST()) {
            $newProduct = $this->request->getJsonRawBody();
            $this->products->addNewProduct($newProduct);
        }
    }
    /**
     * handle webhook request of product stock change
     *
     * @return void
     */
    public function productStockUpdateAction(): void
    {
        if ($this->request->isPOST()) {
            $productData = $this->request->getJsonRawBody();
            $this->products->updateProductStock($productData->id, $productData->stock);
        }
    }
    /**
     * handle webhook request of product price changes
     *
     * @return void
     */
    public function productPriceUpdateAction(): void
    {
        if ($this->request->isPOST()) {
            $productData = $this->request->getJsonRawBody();
            $this->products->updateProductPrice($productData->id, $productData->price);
        }
    }
}
