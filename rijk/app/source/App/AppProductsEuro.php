<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;
use Source\Models\ProductsStockEuro;
use Source\Models\Products;

use function JBZoo\Data\json;

class AppProductsEuro
{

    private $view;
    private $acoes;
    private $sessao;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->sessao = new Sessao();
        $this->acoes = new Acoes();
    }


    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('products');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'status' => $item->status == true ? 'Active' : 'Disabled',
                    'id_crop' => $this->acoes->getData('productsCrop', $item->id_crop, 'name'),
                    'id_variety' => $this->acoes->getData('productsVariety', $item->id_variety, 'name'),
                    'id_sales_unit' => $this->acoes->getData('productsSalesUnit', $item->id_sales_unit, 'type'),
                    'id_chemical_treatment' => $this->acoes->getData('productsChemicalTreatment', $item->id_chemical_treatment, 'name'),
                    'stock' =>  $this->acoes->getByField('productsStockEuro', 'id_products',  $item->id)->quantity,
                    'batch' => $item->batch,
                    'maturity' => $item->maturity,
                    'actions' =>
                    $this->sessao->getRoles() != 2 ?
                        '<a class="btn btn-info mr-2" href="' . ROOT . '/product/stock/item/' . $item->id . '"><i class="fa fa-box-open"></i></a> <button class="btn btn-success" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button>'
                        : '',
                ];
            }
        } else {
            $its = 0;
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }


    public function listView(): void
    {
        $count = $this->acoes->countAdd('products');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('products', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/products/main", [
                "title" => "Products",
                "description" => "List of all products registered in our system",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'productsCrop' => $this->acoes->getFind('productsCrop'),
                'calibre' => $this->acoes->getFind('productsCalibre'),
                'stock' => $this->acoes->getFind('productsStockEuro'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {
        $status = $data['status'] ? true : 'false';
        $item = new Products();

        $item->name = $data['input_name'];
        $item->id_crop = $data['id_category'];
        $item->id_variety = $data['id_variety'];
        $item->id_sales_unit = $data['id_sales_unit'];
        $item->id_chemical_treatment = $data['id_chemical_treatment'];
        $item->batch = $data['batch'];
        $item->maturity = $data['maturity'];
        $item->status = $status;
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Product registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the Product"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('products', 'id', $data['id']);

        header('Content-Type: application/json');
        $json = json_encode([
            'id' => $itens->id,
            'status' => $itens->status == true ? true : false,
            'name' => $itens->name,
            'id_crop' => $itens->id_crop,
            'id_variety' => $itens->id_variety,
            'id_sales_unit' => $itens->id_sales_unit,
            'id_chemical_treatment' => $itens->id_chemical_treatment,
            'stock' =>  $this->acoes->getByField('productsStockEuro', 'id_products',  $itens->id)->quantity,
            'maturity' => $itens->maturity,
            'batch' => $itens->batch,
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $status = $data['status'] ? true : 'false';

        $item = (new Products())->findById($data['id']);
        $item->name = $data['input_name'];
        $item->id_crop = $data['id_crop'];
        $item->id_variety = $data['id_variety'];
        $item->id_sales_unit = $data['id_sales_unit'];
        $item->id_chemical_treatment = $data['id_chemical_treatment'];
        $item->batch = $data['batch'];
        $item->maturity = $data['maturity'];
        $item->status = $status;

        $item->save();

        //dd($item);

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Item <strong>{$date['name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['name']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateActionStock($data): void
    {
        $stock = (new ProductsStockEuro())->findById($data['stock_id']);
        $stock->quantity = $data['quantity'];
        $stock->id_package = $data['id_package'];
        $stock->save();


        if ($stock->id > 0) {
            $status = $data['status'] ? true : 'false';

            $item = (new Products())->findById($data['id']);
            $item->name = $data['input_name'];
            $item->id_crop = $data['id_category'];
            $item->id_variety = $data['id_variety'];
            $item->id_sales_unit = $data['id_sales_unit'];
            $item->id_chemical_treatment = $data['id_chemical_treatment'];
            $item->batch = $data['batch'];
            $item->maturity = $data['maturity'];
            $item->status = $status;
            $item->save();
        }
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Item <strong>{$date['name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['name']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new Products())->findById($data['id']);
        $itemStock = (new ProductsStockEuro())->findById($item->id_stock);

        $item->destroy();
        $itemStock->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}