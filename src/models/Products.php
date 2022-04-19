<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;
use Phalcon\Messages\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;

class Products
{
    function get()
    {
        $product =['name' => 'Product2', 'price' => 200];

        return json_encode($product);
    }
    
}
