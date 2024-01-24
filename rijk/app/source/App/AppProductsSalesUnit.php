<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\ProductsSalesUnit;

use function JBZoo\Data\json;

class AppProductsSalesUnit
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
        $itens = $this->acoes->getFind('productsSalesUnit');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'type' => $item->type,
                    'info' => $item->info,
                    'actions' => '
                    <button class="btn btn-success" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button> <button onclick="deletar(' . $item->id . ')" class="btn btn-danger"><span class="fa fa-times"></span></button>',
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
        $count = $this->acoes->countAdd('productsSalesUnit');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('productsSalesUnit', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/productsSalesUnit/main", [
                "title" => "Sales unit",
                "description" => "Product sales unit",
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
        $itens = $this->acoes->getByField('productsSalesUnit', 'type', $data['type']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "There is already a Sales Unit with this name! Try a new one."]);
        } else {

            $item = new ProductsSalesUnit();

            $item->type = $data['type'];
            $item->info = $data['info'];
            $item->save();
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/sales-unit", 'mensagem' => "Item <strong>{$data['type']}</strong> successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item: {$data['type']}"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('productsSalesUnit', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['type' => $itens->type, 'info' => $itens->info, 'id' => $itens->id]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new ProductsSalesUnit())->findById($data['id']);
        $item->type = $data['type'];
        $item->info = $data['info'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/sales-unit", 'mensagem' => "Item <strong>{$data['type']}</strong> atualizado com sucesso"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$data['type']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new ProductsSalesUnit())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/sales-unit", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
