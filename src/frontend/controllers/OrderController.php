<?php

use Phalcon\Mvc\Controller;


class OrderController extends Controller
{

    public function viewAllOrdersAction()
    {
        /**
         * view all orders
         *
         * @return void
         */
        $orders = new Order;
        $this->view->orders = $orders->getAllOrders();
    }
    
}
