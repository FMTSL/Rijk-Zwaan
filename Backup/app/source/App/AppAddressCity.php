<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\address;

use function JBZoo\Data\json;

class AppAddress
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
        $count = $this->acoes->countAdd('address_city');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('address', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/address/city", [
                "title" => "Adresses",
                "description" => "Product addresses",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'pager' => $pager->render('p-3 page'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {
        $itens = $this->acoes->getByField('address_city', 'name', $data['input_name']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "An address with this name already exists! Try a new one."]);
        } else {

            $item = new Address();
            $item->address_1 = $data['address_1'];
            $item->address_2 = $data['address_2'];
            $item->zipcode = $data['zipcode'];
            $item->id_city = $data['id_city'];
            $item->id_state = $data['id_state'];
            $item->id_district = $data['id_district'];
            $item->save();
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/adresses", 'mensagem' => "Addresses registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the addresses"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('address_city', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'address_1' => $itens->address_1,
            'address_2' => $itens->address_2,
            'zipcode' => $itens->zipcode,
            'id_city' => $itens->id_city,
            'id_state' => $itens->id_state,
            'id_district' => $itens->id_district,
            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new Address())->findById($data['id']);
        $item->address_1 = $data['address_1'];
        $item->address_2 = $data['address_2'];
        $item->zipcode = $data['zipcode'];
        $item->id_city = $data['id_city'];
        $item->id_state = $data['id_state'];
        $item->id_district = $data['id_district'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/adresses", 'mensagem' => "Address City updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update Address City"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new Address())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/adresses", 'mensagem' => "Address City successfully deleted"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
