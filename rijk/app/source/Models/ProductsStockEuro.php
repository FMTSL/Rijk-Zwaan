<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class ProductsStockEuro extends DataLayer
{
    public function __construct()
    {
        parent::__construct("products_stock_euro", []);
    }

    public function add($article_number, ProductsVariety $variety, $quantity, $id_package, $value, $weight, $status, $sub_article_number)
    {
        $this->quantity = $quantity;
        $this->id_package = $id_package;
        $this->article_number = $article_number;
        $this->sub_article_number = $sub_article_number;
        $this->id_variety = $variety->id;
        $this->value = $value;
        $this->weight = $weight;
        $this->status = $status;

        $this->save();
        return $this;
    }
}