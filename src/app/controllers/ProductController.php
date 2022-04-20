<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Products;

/**
 * @property Response $response
 */
class ProductController extends Controller
{
    public function index()
    {
        
    }
    /**
     * get all products
     *
     * @return void
     */
    public function getAllProducts()
    {
        $product = new Products;
        $products = $product->getAllProducts();
        $result = array();
        foreach ($products as $p) {
            array_push($result, array(
                "name"       =>   $p->name,
                "category"   =>   $p->category,
                "price"      =>   $p->price,
                "stock"      =>   $p->stock
            ));
        }
        $content = [
            'type' => 'get',
            'products' => $result,
            'database' => 'store',
            'document' => 'products'
        ];
        $this->response->setStatusCode(200, 'Products found')->setJsonContent($content);

        return $this->response;
    }
    /**
     * search product by name
     *
     * @param [type] $name
     * @return void
     */
    public function getProductByName($name)
    {
        $param = explode("+", $name);
        $product = new Products;
        $products = $product->getProductByName($param);
        $result = array();
        foreach ($products as $k=>$p) {
            $result[$k] = json_decode(json_encode($p), true);
        }
        $content = [
            'type' => 'get',
            'products' => $result,
            'database' => 'store',
            'document' => 'products'
        ];
        $this->response->setStatusCode(200, 'Products found')->setJsonContent($content);
        return $this->response;
    }
    public function getProductsByPage($limit, $page)
    {
        $product = new Products;
        $products = $product->getProductsByLimit((int)$limit, (int)$page);
        $result = array();
        foreach ($products as $k=>$p) {
            $result[$k] = json_decode(json_encode($p), true);
        }
        $content = [
            'type' => 'get',
            'products' => $result,
            'database' => 'store',
            'document' => 'products'
        ];
        $this->response->setStatusCode(200, 'Products found')->setJsonContent($content);

        return $this->response;
    }
    public function getProductsByColumns()
    {
        $product = new Products;
        $request = new Request();
        $this->response->stream_get_content($request->getPost());
        return $this->response;
        // $products = $product->getProductsByLimit((int)$limit, (int)$page);
        // $result = array();
        // foreach ($products as $k=>$p) {
        //     $result[$k] = json_decode(json_encode($p), true);
        // }
        // $content = [
        //     'type' => 'get',
        //     'products' => $result,
        //     'database' => 'store',
        //     'document' => 'products'
        // ];
        // $this->response->setStatusCode(200, 'Products found')->setJsonContent($content);

        // return $this->response;
    }
}
