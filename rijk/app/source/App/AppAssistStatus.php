<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\AssistStatus;

use function JBZoo\Data\json;

class AppAssistStatus
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
        $itens = $this->acoes->getFind('assistStatus');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
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
    public function listView($data): void
    {
        $count = $this->acoes->countAdd('assistStatus');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('assistStatus', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/assistStatus/main", [
                "title" => "Order status",
                "description" => "Statuses registered in the system",
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
        $item = new AssistStatus();

        $item->name = $data['input_name'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/request-status", 'mensagem' => "Item <strong>{$date['name']}</strong> successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item: {$data['input_name']}"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('assistStatus', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['name' => $itens->name, 'id' => $itens->id]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new AssistStatus())->findById($data['id']);
        $item->name = $data['status_name'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/request-status", 'mensagem' => "Item <strong>{$date['name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['name']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new AssistStatus())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/request-status", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
