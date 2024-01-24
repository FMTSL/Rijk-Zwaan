<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class OrderCart extends DataLayer
{
    public function __construct()
    {
        parent::__construct("order_cart", []);
    }

    public function add(
        $article_number,
        $value,
        $weight,
        $id_order,
        $id_stock,
        $quantity,
        $bonus_order,
        $id_customer,
        $early_discount,
        $volume_condition,
        $aditional_discount,
        $category_discount,
        $salanova,
        $total_discount,
        $order_status,
        $bonus_type,
        $value_not_discount,
        $price,
        $value_icms
    ) {
        $this->article_number = $article_number;
        $this->id_customer = $id_customer;
        $this->id_stock = $id_stock;
        $this->value = $value;
        $this->value_not_discount = $value_not_discount;
        $this->category_discount = $category_discount;
        $this->quantity = $quantity;
        $this->early_discount = $early_discount;
        $this->id_order = $id_order;
        $this->weight = $weight;
        $this->volume_condition = $volume_condition;
        $this->aditional_discount = $aditional_discount;
        $this->bonus_order = $bonus_order;
        $this->salanova = $salanova;
        $this->total_discount = $total_discount;
        $this->order_status = $order_status;
        $this->bonus_type = $bonus_type;
        $this->price = $price;
        $this->value_icms = $value_icms;


        $this->save();
        return $this;
    }
}