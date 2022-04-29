<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Order extends Model
{
    /**
     * initializing mongo constructor
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->order;
    }
    /**
     * get all orders from db
     *
     * @return array
     */
    public function getAllOrders(): array
    {
        return $this->collection->find();
    }
}
