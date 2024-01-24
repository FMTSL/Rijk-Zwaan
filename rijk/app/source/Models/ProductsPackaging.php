<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class ProductsPackaging extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("products_packaging", []);
        
    }

    public function add(string $name)
    {
        
        $this->name = $name;

        $this->save();
        return $this;
    }
}