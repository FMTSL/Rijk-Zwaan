<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Discount extends DataLayer
{


    public function __construct()
    {
        parent::__construct("discount", []);
    }

    public function add($value, $percentage, $id_variety)
    {
        $this->value = $value;
        $this->percentage = $percentage;
        $this->id_variety = $id_variety;


        $this->save();
        return $this;
    }
}
