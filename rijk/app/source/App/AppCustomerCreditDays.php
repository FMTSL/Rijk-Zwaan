<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Category;
use Source\Models\CustomerCreditDays;

use function JBZoo\Data\json;

class AppCustomerCreditDays
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
        $itens = $this->acoes->getFind('customerCreditDays');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'days' => $item->days,
                    'ticket' => $item->ticket,
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



    public function listView(): void
    {
        $count = $this->acoes->countAdd('customerCreditDays');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('customerCreditDays', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/customerCreditDays/main", [
                "title" => "Billing term",
                "description" => "Order invoicing period",
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
        $itens = $this->acoes->getByField('customerCreditDays', 'days', $data['days']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "There is already a billing period with this name! try a new one."]);
        } else {

            $item = new CustomerCreditDays();

            $item->days = $data['days'];
            $item->ticket = $data['ticket'];
            $item->save();
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/billing-term", 'mensagem' => "Billing registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the Item"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('customerCreditDays', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'days' => $itens->days,
            'ticket' => $itens->ticket,
            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new CustomerCreditDays())->findById($data['id']);
        $item->name = $data['input_name'];
        $item->slug = $data['slug'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/billing-term", 'mensagem' => "Item <strong>{$date['name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['name']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new CustomerCreditDays())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/billing-term", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
