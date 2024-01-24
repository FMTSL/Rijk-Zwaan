<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class ProductsChemicalTreatment extends DataLayer
{
    public function __construct()
    {
        parent::__construct("products_chemical_treatment", ["name"]);
        
    }

    public function add(string $name)
    {
        
        $this->name = $name;

        $this->save();
        return $this;
    }
}