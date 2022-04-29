<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class ProductController extends Controller
{
    /**
     * initializing product collection object
     *
     * @return void
     */
    public function initialize():void
    {
        $this->products = new Products();
    }
    /**
     * get all products
     *
     * @return void
     */
    public function allProductsAction(): void
    {
        $this->view->products = $this->products->getAllProducts();
    }
    /**
     * add new product to product collection
     *
     * @return void
     */
    public function addNewAction(): void
    {
        if ($this->request->isPost()) {
            $product = array(
                'name' => $this->escaper->escapeHtml($this->request->getPost('name')),
                'category' => $this->escaper->escapeHtml($this->request->getPost('category')),
                'price' => $this->escaper->escapeHtml($this->request->getPost('price')),
                'stock' => $this->escaper->escapeHtml($this->request->getPost('stock'))
            );
            $product_id = $this->products->addNewProduct($product);
            $this->flash->success('New Product Added.');
            $product = $this->products->getProductById($product_id);
            /**
             * fire new product webhook event
             */
            $this->di->get('EventsManager')->fire('webhooks:newProductWebhook:', $this, $product);
        }
    }
    /**
     * update stock of product in product collection
     *
     * @return void
     */
    public function updateStockAction(): void
    {
        if($this->request->isPost()){
            $product_id = $this->request->getPost('product_id');
            $stock = $this->request->getPost('stock');
            $this->products->updateProductStock($product_id, $stock);
            /**
             * fire product stock update webhook event
             */
            $this->di->get('EventsManager')->fire('webhooks:updateStockWebhook:', $this, ['id'=>$product_id, 'stock'=>$stock]);
        }
    }
    /**
     * update product price in product collection
     *
     * @return void
     */
    public function updatePriceAction(): void
    {
        if($this->request->isPost()){
            $product_id = $this->request->getPost('product_id');
            $price = $this->request->getPost('price');
            $this->products->updateProductPrice($product_id, $price);
            /**
             * fire product price update webhook event
             */
            $this->di->get('EventsManager')->fire('webhooks:updatePriceWebhook:', $this, ['id'=>$product_id, 'price'=>$price]);
        }
    }
}
