<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class ProductsStockClone extends DataLayer
{
    public function __construct()
    {
        parent::__construct("products_stock_clone", []);
    }

    public function add($variety, $crop, $batch, $product_sales_unit, $one_of_sale, $packaging_expiration, $treatments, $sum_of_qty_in_vwh_local)
    {
        $this->id_crop = $crop;
        $this->batch = $batch;
        $this->product_sales_unit = $product_sales_unit;
        $this->id_variety = $variety;
        $this->one_of_sale = $one_of_sale;
        $this->packaging_expiration = $packaging_expiration;
        $this->treatments = $treatments;
        $this->sum_of_qty_in_vwh_local = $sum_of_qty_in_vwh_local;

        $this->save();
        return $this;
    }
}