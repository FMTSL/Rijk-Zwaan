<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class ProductsCrop extends DataLayer
{
    public function __construct()
    {
        parent::__construct("products_crop", []);
    }

    public function add(string $name)
    {
        $this->name = $name;

        $this->save();
        return $this;
    }
}