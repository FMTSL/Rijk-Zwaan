<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class AssistDeliveryType extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("assist_delivery_type", []);
        
    }

    public function add(string $name)
    {
        $this->name = $name;
        $this->save();
        return $this;
    }
}