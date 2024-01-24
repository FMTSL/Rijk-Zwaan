<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Category;
use Source\Models\CustomerCreditDeadline;
use Source\Models\CustomerPaymentType;

use function JBZoo\Data\json;

class AppCustomerPaymentType
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
                    'id_crop' => $this->acoes->getData('productsCrop', $item->id_crop, 'name'),
                    'id_variety' => $this->acoes->getData('productsVariety', $item->id_variety, 'name'),
                    'id_sales_unit' => $this->acoes->getData('productsSalesUnit', $item->id_sales_unit, 'type'),
                    'id_chemical_treatment' => $this->acoes->getData('productsChemicalTreatment', $item->id_chemical_treatment, 'name'),
                    'batch' => $item->batch,
                    'maturity' => $item->maturity,
                    'actions' => '<a class="btn btn-info" href="' . ROOT . '/product/stock/item/' . $item->id . '"><i class="fa fa-box-open"></i></a><button class="btn btn-success" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button>',
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
        $count = $this->acoes->countAdd('customerPaymentType');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('customerPaymentType', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/customerPaymentType/main", [
                "title" => "Payment term",
                "description" => "Registered Payment term",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'pager' => $pager->render('p-3 page'),
                'categoryCustomer' => $this->acoes->getFind('customerPaymentType'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {
        $item = new CustomerPaymentType();
        $item->name = $data['name_type'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/payment-type", 'mensagem' => "Item registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the Item"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('customerPaymentType', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'name' => $itens->name,
            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new CustomerPaymentType())->findById($data['id']);
        $item->name = $data['name_type'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/payment-type", 'mensagem' => "Item updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new CustomerPaymentType())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/payment-type", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
