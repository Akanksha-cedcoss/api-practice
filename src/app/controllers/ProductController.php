<?php

use Phalcon\Mvc\Controller;


class ProductController extends Controller
{
    public $webhooks;

    function initialize()
    {
        $this->products = new Products;
    }
    /**
     * get all products
     *
     * @return void
     */
    public function allProductsAction()
    {
        $this->view->products = $this->products->getAllProducts();
        if (isset($_POST['stockbtn'])) {
            $stock = $_POST['stock'];
            die($stock);
            $product_id = $_POST['product_id'];
            
        }
        elseif (isset($_POST['pricebtn'])) {
            $price = $_POST['price'];
            $product_id = $_POST['product_id'];
            $this->products->updateProductPrice($product_id, $price);
            $this->di->get('EventsManager')->fire('webhooks:updatePriceWebhook:', $this, ['id'=>$product_id, 'price'=>$price]);
        }
    }
    public function addNewAction()
    {
        if ($_POST) {
            $product = array(
                'name' => $this->escaper->escapeHtml($this->request->getPost('name')),
                'category' => $this->escaper->escapeHtml($this->request->getPost('category')),
                'price' => $this->escaper->escapeHtml($this->request->getPost('price')),
                'stock' => $this->escaper->escapeHtml($this->request->getPost('stock'))
            );
            $product_id = $this->products->addNewProduct($product);
            $this->flash->success('New Product Added.');
            $product = $this->products->getProductById($product_id);
            $this->di->get('EventsManager')->fire('webhooks:newProductWebhook:', $this,$product);
        }
    }
    public function updateStockAction()
    {
        if($_POST){
            $product_id = $this->request->getPost('product_id');
            $stock = $this->request->getPost('stock');
            $this->products->updateProductStock($product_id, $stock);
            $this->di->get('EventsManager')->fire('webhooks:updateStockWebhook:', $this, ['id'=>$product_id, 'stock'=>$stock]);
        }
    }
    public function updatePriceAction()
    {
        if($_POST){
            $product_id = $this->request->getPost('product_id');
            $price = $this->request->getPost('price');
            $this->products->updateProductPrice($product_id, $price);
            $this->di->get('EventsManager')->fire('webhooks:updatePriceWebhook:', $this, ['id'=>$product_id, 'price'=>$price]);
        }
    }
}
