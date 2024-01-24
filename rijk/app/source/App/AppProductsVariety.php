<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;

use Source\Models\ProductsVariety;
use Source\Models\Variety;
use function JBZoo\Data\json;

class AppProductsVariety
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
        $itens = $this->acoes->getFind('productsVariety');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
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
        $count = $this->acoes->countAdd('productsVariety');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('productsVariety', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/productsVariety/main", [
                "title" => "Variety",
                "description" => "Variety of products",
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

    public function listViewId($data): void
    {
        $itens = $this->acoes->getByFieldAll('productsVariety', 'id', $data['id_variety']);

        foreach ($itens as &$item) {
            $its[] = [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
            ];
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }

    public function newAction($data): void
    {

        $itens = $this->acoes->getByField('productsVariety', 'name', $data['input_name']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "There is already a Variety with this name! Try a new one."]);
        } else {

            $item = new ProductsVariety();
            $item->name = $data['input_name'];
            $item->slug = $data['slug'];
            $item->save();

            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/varieties", 'mensagem' => "Item <strong>{$date['name']}</strong> successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item: {$data['input_name']}"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('productsVariety', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['name' => $itens->name, 'slug' => $itens->slug, 'id' => $itens->id]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new ProductsVariety())->findById($data['id']);
        $item->name = $data['input_name'];
        $item->slug = $data['slug'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/varieties", 'mensagem' => "Item <strong>{$date['name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['name']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new ProductsVariety())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/varieties", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
