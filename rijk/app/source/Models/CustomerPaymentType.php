<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class CustomerPaymentType extends DataLayer
{
    public function __construct()
    {
        parent::__construct("customer_payment_type", []);
    }

    public function add(string $name)
    {
        
        $this->name = $name;

        $this->save();
        return $this;
    }
}