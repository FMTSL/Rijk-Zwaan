<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

class Customers extends DataLayer
{

    public function __construct()
    {
        parent::__construct("customers", []);
    }

    public function add(
        Users $users,
        Users $cli,
        CustomerCategory $category,
        Address $address,
        int $special_client,
        string $email,
        string $fax,
        string $bio,
        string $mobile,
        string $website,
        string $full_name,
        string $telephone,
        $relation_number,
        int $id_category,
        string $cnpj,
        bool $euro // Campo 'euro'
    ) {
        $this->id_salesman = $users->id;
        $this->id_customer = $cli->id;
        $this->id_category_customer = $category->id;
        $this->id_address = $address->id;
        $this->email = $email;
        $this->fax = $fax;
        $this->bio = $bio;
        $this->mobile = $mobile;
        $this->website = $website;
        $this->full_name = $full_name;
        $this->telephone = $telephone;
        $this->relation_number = $relation_number;
        $this->id_category = $id_category;
        $this->cnpj = $cnpj;
        $this->special_client = $special_client;
        $this->euro = $euro; // Campo 'euro'
        $this->save();
        return $this;
    }
}
