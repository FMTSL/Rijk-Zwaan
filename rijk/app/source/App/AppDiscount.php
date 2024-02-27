<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Discount;

use function JBZoo\Data\json;

class AppDiscount
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

    public function listView($data): void
    {
        // Lista apenas descontos em euros
        $itens = $this->acoes->getByField('discount', 'currency', 'EUR');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/discount/main", [
                "title" => "Discounts",
                "description" => "Discount registered in the system",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'productsVariety' => $this->acoes->getFind('productsVariety'),
                "itens" => $itens,
            ]);
        } else {
            redirect("/login");
        }
    }


    public function listViewAll($data): void
    {
        // Lista todos os descontos em formato JSON
        $itens = $this->acoes->getFind('discount');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'value' => $item->value,
                    'percentage' => $item->percentage . '%',
                    'id_variety' => $this->acoes->getData('productsVariety', $item->id_variety, 'name'),
                    'actions' => ' <button class="btn btn-success" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button> <button onclick="deletar(' . $item->id . ')" class="btn btn-danger"><i class="fa fa-times"></i></button>',
                ];
            }
        } else {
            $its = 0;
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }

    public function newAction($data): void
    {
        // Insere novo desconto em euros
        $valor = str_replace(".", "", $data['value']);

        $item = new Discount();
        $item->value = $valor;
        $item->percentage = $data['percentage'];
        $item->id_variety = $data['id_variety'];
        $item->currency = 'EUR'; // Moeda em euros

        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/discounts", 'mensagem' => "Item <strong>{$data['value']}</strong> successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item: {$data['value']}"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('discount', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['value' => $itens->value, 'percentage' => $itens->percentage, 'id_variety' => $itens->id_variety, 'id' => $itens->id]);
        exit($json);
    }

    public function listViewId($data): void
    {
        $itens = $this->acoes->getByFieldAll('discount', 'id_variety', $data['id']);
        $client = $this->acoes->getByField('customers', 'id', $data['idClient']);

        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'value' => $item->value,
                    'percentage' => $item->percentage,
                    'id_variety' => $item->id_variety,
                    'special_client' => $client->special_client,
                ];
            }
        } else {
            $its = 0;
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateAction($data): void
    {
        $valor = str_replace(".", "", $data['value']);

        $item = (new Discount())->findById($data['id']);
        $item->value = $valor;
        $item->percentage = $data['percentage'];
        $item->id_variety = $data['id_variety'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/discounts", 'mensagem' => "Item <strong>{$data['value']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$data['value']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new Discount())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/discounts", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function listViewEuro($data): void
    {
        // Lista apenas descontos em euros
        $itens = $this->acoes->getByField('discount', 'currency', 'EUR');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/discount/main", [
                "title" => "Discounts in Euro",
                "description" => "Discounts registered in euros",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'productsVariety' => $this->acoes->getFind('productsVariety'),
                "itens" => $itens,
            ]);
        } else {
            redirect("/login");
        }
    }

}