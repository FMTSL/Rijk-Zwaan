<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class ProductsCalibre extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("products_calibre", ["name"]);
        
    }

    public function add(string $name)
    {
        
        $this->name = $name;

        $this->save();
        return $this;
    }
}