<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class OrderController extends Controller
{
    /**
     * load all orders
     *
     * @return void
     */
    public function viewAllOrdersAction(): void
    {
        /**
         * view all orders
         *
         * @return void
         */
        $orders = new Order();
        $this->view->orders = $orders->getAllOrders();
    }
}
