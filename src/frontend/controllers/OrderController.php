<?php

use Phalcon\Mvc\Controller;


class OrderController extends Controller
{
    public $orders;

    function initialize()
    {
        $this->orders = new Order;
    }

    public function viewAllOrdersAction()
    {
        /**
         * view all orders
         *
         * @return void
         */
        $this->view->orders = $this->orders->getAllOrders();
    }
}
