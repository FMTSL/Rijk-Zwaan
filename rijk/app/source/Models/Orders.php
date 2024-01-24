<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Orders extends DataLayer
{


    public function __construct()
    {
        parent::__construct("orders", []);
    }

    public function add(Users $client, $net_value,  $value_total, $value, $discount, $status, $order_number, $payment_type, $cash_payment, $order_date, $delivery, $comments, $id_payment_term, $delivery_address, $tax, $freight, $tax_value)
    {

        $this->id_customer = $client->id;
        $this->value = $value;
        $this->value_total = $value_total;
        $this->discount = $discount;
        $this->order_number = $order_number;
        $this->payment_type = $payment_type;
        $this->cash_payment = $cash_payment;
        $this->order_date = $order_date;
        $this->status = $status;
        $this->delivery = $delivery;
        $this->comments = $comments;
        $this->id_payment_term = $id_payment_term;
        $this->delivery_address = $delivery_address;
        $this->tax = $tax;
        $this->freight = $freight;
        $this->tax_value = $tax_value;
        $this->net_value = $net_value;


        $this->save();
        return $this;
    }
}