<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class ProductsSalesUnit extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("products_sales_unit", ["type", "info"]);
    }

    public function add(string $type, string $info)
    {
        
        $this->type = $type;
        $this->info = $info;

        $this->save();
        return $this;
    }
}