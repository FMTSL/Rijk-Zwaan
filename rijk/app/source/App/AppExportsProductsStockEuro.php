<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;

use League\Csv\Writer;
use Bcrypt\Bcrypt;

class AppExportsProductsStockEuro
{
    private $acoes;


    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->sessao = new Sessao();
        $this->bcrypt = new Bcrypt();
        $this->acoes = new Acoes();
    }


    public function newAction($data): void
    {
        $products = $this->acoes->getFind('productsStockEuro');
        $csv = Writer::createFromString("");
        $csv->insertOne([
            'ID',
            'Article Number',
            'Sub Article Number',
            'Sales Variety',
            'Packaging Size and Unit',
            'Quantity',
            'Price',
            'Weight',
            'Status',
        ]);

        foreach ($products as $prod) {
            $csv->insertOne([
                $prod->id,
                $prod->article_number,
                $prod->sub_article_number,
                $prod->id_variety ? $this->acoes->getData('productsVariety', $prod->id_variety, 'name') : '',
                $prod->id_package ? $this->acoes->getByField('productsPackaging', 'id', $prod->id_package)->name : '',
                $prod->quantity,
                $prod->value,
                $prod->weight,
                $prod->status == true ? "S" : "N",
            ]);
        }
        $csv->output("products-stock.csv");
    }
}