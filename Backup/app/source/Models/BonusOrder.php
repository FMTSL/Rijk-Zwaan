<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class BonusOrder extends DataLayer
{


    public function __construct()
    {
        parent::__construct("bonus_order", []);
    }

    public function add($name, $discount)
    {
        $this->name = $name;
        $this->discount = $discount;

        $this->save();
        return $this;
    }
}
