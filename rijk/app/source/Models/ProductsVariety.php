<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;



class ProductsVariety extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("products_variety", ["name"]);
        
    }

    public function add(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;

        $this->save();
        return $this;
    }
}