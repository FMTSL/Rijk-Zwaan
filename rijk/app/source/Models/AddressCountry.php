<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class AddressCountry extends DataLayer
{
    
    public function __construct()
    {
        parent::__construct("address_country", []);
        
    }

    public function add(string $name, $name_pt, $initials, $bacen)
    {
        
        $this->name = $name;
        $this->name_pt = $name_pt;
        $this->initials = $initials;
        $this->bacen = $bacen;

        $this->save();
        return $this;
    }
}