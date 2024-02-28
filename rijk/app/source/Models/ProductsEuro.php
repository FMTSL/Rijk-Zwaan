<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Products extends DataLayer
{


    public function __construct()
    {
        parent::__construct("products-euro", []);
    }

    public function add(string $name, ProductsCalibre $category, PStockEuro $variety, ProductsSalesUnit $sales_unit, ProductsChemicalTreatment $chemical_treatment, ProductsStock $stock, $batch, $maturity, $status)
    {

        $this->name = $name;
        $this->id_category = $category->id;
        $this->id_variety = $variety->id;
        $this->id_sales_unit = $sales_unit->id;
        $this->id_chemical_treatment = $chemical_treatment->id;
        $this->id_stock = $stock->id;
        $this->maturity = $maturity;
        $this->batch = $batch;
        $this->status = $status;

        $this->save();
        return $this;
    }
}
