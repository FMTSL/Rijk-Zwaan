<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

class Salesman extends DataLayer
{
    public function __construct()
    {
        parent::__construct("salesman", []);   
    }

    public function add(string $name, $full_name, $genre, $birthdate,  Users $users)
    {
        $this->name = $name;
        $this->full_name = $full_name;
        $this->genre = $genre;
        $this->birthdate = $birthdate;
        $this->id_user = $users->id;

        $this->save();
        return $this;
    }
}