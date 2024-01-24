<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class AssistStatus extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("assist_status", []);
        
    }

    public function add(string $name)
    {
        
        $this->name = $name;

        $this->save();
        return $this;
    }
}