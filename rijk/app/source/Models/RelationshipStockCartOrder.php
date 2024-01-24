<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class RelationshipStockCartOrder extends DataLayer
{


    public function __construct()
    {
        parent::__construct("relationship_stock_cart_order", []);
    }

    public function add(ProductsStock $stock, OrderCart $cart_order, $old_quantity)
    {
        $this->id_stock = $stock->id;
        $this->id_cart_order = $cart_order->id;
        $this->old_quantity = $old_quantity;
        $this->save();
        return $this;
    }
}