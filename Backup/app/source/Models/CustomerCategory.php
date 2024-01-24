<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class CustomerCategory extends DataLayer
{
    
    public function __construct()
    {
        parent::__construct("customer_category", []);
        
    }

    public function add(string $name, $basic_discount, $cash_payment_discount, $goal_discount, $goal_introduction, $code)
    {
        
        $this->name = $name;
        $this->basic_discount = $basic_discount;
        $this->cash_payment_discount = $cash_payment_discount;
        $this->goal_discount = $goal_discount;
        $this->goal_introduction = $goal_introduction;
        $this->code = $code;

        $this->save();
        return $this;
    }
}