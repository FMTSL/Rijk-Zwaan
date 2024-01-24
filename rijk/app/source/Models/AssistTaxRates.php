<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class AssistTaxRates extends DataLayer
{


    public function __construct()
    {
        parent::__construct("assist_tax_rates", ["name_tax", "tax", "id_country", "id_state"]);
    }

    public function add(string $name_tax, string $tax, string $id_country, string $id_state)
    {

        $this->name_tax = $name_tax;
        $this->tax = $tax;
        $this->id_country = $id_country;
        $this->id_state = $id_state;

        $this->save();

        return $this;
    }
}
