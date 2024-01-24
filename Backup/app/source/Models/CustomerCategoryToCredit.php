<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class CustomerCategoryToCredit extends DataLayer
{
    
    public function __construct()
    {
        parent::__construct("customer_category_to_credit", []);
        
    }

    public function add(string $id_category, string $id_credit_deadline)
    {
        
        $this->id_category = $id_category;
        $this->id_credit_deadline = $id_credit_deadline;

        $this->save();
        return $this;
    }
}