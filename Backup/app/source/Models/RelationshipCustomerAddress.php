<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class RelationshipCustomerAddress extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("relationship_customer_address", []);
        
    }

    public function add(int $id_address, int $id_customer)
    {
        $this->id_address = $id_address;
        $this->id_customer = $id_customer;
        $this->save();
        return $this;
    }
}