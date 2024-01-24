<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class OrderLogistics extends DataLayer
{


    public function __construct()
    {
        parent::__construct("order_logistics", []);
    }

    public function add(AssistStatus $status, Orders $order, $tracking_code, $information)
    {

        $this->id_status = $status->id;
        $this->id_order = $order->id;
        $this->tracking_code = $tracking_code;
        $this->information = $information;

        $this->save();
        return $this;
    }
}
