<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\AssistTaxRates;

use function JBZoo\Data\json;

class AppAssistTaxRates
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
        $count = $this->acoes->countAdd('assistTaxRates');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('assistTaxRates', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/assistTaxRates/main", [
                "title" => "Order Tax Rates",
                "description" => "Tax Rates registered in the system",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'countrys' => $this->acoes->getFind('addressCountry'),
                'states' => $this->acoes->getFind('addressState'),
                'pager' => $pager->render('p-3 page'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('assistTaxRates');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name_tax' => $item->name_tax,
                    'tax' => $item->tax,
                    'id_country' => $this->acoes->getData('addressCountry', $item->id_country, 'name'),
                    'id_state' => $this->acoes->getData('addressState', $item->id_state, 'name'),
                    'actions' => '<button class="btn btn-success mr-2" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button>',
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
        $item = new AssistTaxRates();

        $item->name_tax = $data['name_tax'];
        $item->tax = $data['tax'];
        $item->id_country = $data['id_country'];
        $item->id_state = $data['id_state'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/assists-tax-rates", 'mensagem' => "Item <strong>{$date['name_tax']}</strong> successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item: {$data['name_tax']}"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('assistTaxRates', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'name_tax' => $itens->name_tax,
            'tax' => $itens->tax,
            'id_country' => $itens->id_country,
            'id_state' => $itens->id_state,
            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new AssistTaxRates())->findById($data['id']);
        $item->name_tax = $data['name_tax'];
        $item->tax = $data['tax'];
        $item->id_country = $data['id_country'];
        $item->id_state = $data['id_state'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/assists-tax-rates", 'mensagem' => "Item <strong>{$date['name_tax']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['name_tax']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new AssistTaxRates())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/assists-tax-rates", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
