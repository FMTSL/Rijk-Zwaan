<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Files extends DataLayer
{

    public function __construct()
    {
        parent::__construct("files", []);
    }

    public function add($name, $file_name)
    {
        $this->name = $name;
        $this->file_name = $file_name;

        $this->save();
        return $this;
    }
}
