<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Salanova extends DataLayer
{


    public function __construct()
    {
        parent::__construct("salanova", []);
    }

    public function add($name, $discount)
    {
        $this->discount = $discount;

        $this->save();
        return $this;
    }
}
