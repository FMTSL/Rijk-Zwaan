<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;


use League\Csv\Writer;
use Bcrypt\Bcrypt;


use function JBZoo\Data\json;

class AppExportsProductsStockClone
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
        $products = $this->acoes->getFind('productsStockClone');
        $csv = Writer::createFromString("");
        $csv->insertOne([
            'ID',
            'Crop',
            'Variedade',
            'Produto + Unidade de venda',
            'Lote',
            'Un de venda',
            'Vencimento embalagem',
            'Tratamentos',
            'Sum of Qty in VWH Local',
        ]);

        foreach ($products as $prod) {
            $csv->insertOne([
                $prod->id,
                $prod->id_crop ? $this->acoes->getData('productsCrop', $prod->id_crop, 'name') : '',
                $prod->id_variety ? $this->acoes->getData('productsVariety', $prod->id_variety, 'name') : '',
                $prod->product_sales_unit,
                $prod->batch,
                $prod->one_of_sale,
                $prod->packaging_expiration,
                $prod->treatments,
                $prod->sum_of_qty_in_vwh_local,
            ]);
        }
        $csv->output("products-stock-clone.csv");
    }
}