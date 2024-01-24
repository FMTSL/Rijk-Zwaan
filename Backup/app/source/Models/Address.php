<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Address extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("address", ["address_1","zipcode", "id_state", "id_country"]);
        
    }

    public function add(string $address_1, $address_2, $zipcode, $city, AddressState $state, AddressCountry $country)
    {
        $this->address_1 = $address_1;
        $this->address_2 = $address_2;
        $this->zipcode = $zipcode;
        $this->city = $city;
        $this->id_state = $state->id;
        $this->id_country = $country->id;

        $this->save();
        return $this;
    }
}