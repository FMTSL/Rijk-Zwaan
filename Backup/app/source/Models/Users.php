<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Users extends DataLayer
{
    

    public function __construct()
    {
        parent::__construct("users", ["name", "email"]);
        
    }

    public function add(string $name, string $email, string $phone, string $password, string $roles, string $status)
    {
        
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->roles = $roles;
        $this->status = $status;

        $this->save();
        return $this;
    }
}