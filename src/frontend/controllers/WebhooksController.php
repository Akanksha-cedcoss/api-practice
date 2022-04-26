<?php

use Phalcon\Mvc\Controller;


class WebhooksController extends Controller
{
    public $products;

    function initialize(){
        $this->products = new Product;
    }
    public function newProductUpdateAction()
    {
        if ($this->request->isPOST()) {
            $newProduct = $this->request->getJsonRawBody();
            $this->products->addNewProduct($newProduct);
        }
    }
    public function productStockUpdateAction()
    {
        if ($this->request->isPOST()) {
            $productData = $this->request->getJsonRawBody();
            $this->products->updateProductStock($productData->id, $productData->stock);
        }
    }
    public function productPriceUpdateAction()
    {
        if ($this->request->isPOST()) {
            $productData = $this->request->getJsonRawBody();
            $this->products->updateProductPrice($productData->id, $productData->price);
        }
    }
}
