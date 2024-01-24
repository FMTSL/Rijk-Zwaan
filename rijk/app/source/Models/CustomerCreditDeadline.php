<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class CustomerCreditDeadline extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("customer_credit_deadline", []);
        
    }

    public function add(string $valor, $deadline, $id_customer_category, $type_payment)
    {
        $this->valor = $valor;
        $this->deadline = $deadline;
        $this->id_customer_category = $id_customer_category;
        $this->type_payment = $type_payment;

        $this->save();
        return $this;
    }
}