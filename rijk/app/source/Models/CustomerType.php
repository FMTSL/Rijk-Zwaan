<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class CustomerType extends DataLayer
{


    public function __construct()
    {
        parent::__construct("customer_type", []);
    }

    public function add(string $name, string $slug)
    {

        $this->name = $name;
        $this->slug = $slug;

        $this->save();
        return $this;
    }
}
