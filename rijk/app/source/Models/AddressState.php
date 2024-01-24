<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class AddressState extends DataLayer
{
    
    public function __construct()
    {
        parent::__construct("address_state", []);
        
    }

    public function add(string $name, $uf, $ibge, $country,$ddd)
    {
        
        $this->name = $name;
        $this->uf = $uf;
        $this->ibge = $ibge;
        $this->country = $country;
        $this->ddd = $ddd;

        $this->save();
        return $this;
    }
}