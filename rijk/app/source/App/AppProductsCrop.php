<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\ProductsCrop;

use function JBZoo\Data\json;

class AppProductsCrop
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
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/productsCrop/main", [
                "title" => "Products Crop",
                "description" => "Products Crop",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $this->acoes->getFind('productsCrop'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('productsCrop');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
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

    public function newAction($data): void
    {
        $item = new ProductsCrop();

        $item->name = $data['input_name'];
        $item->save();
        //dd($item);
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products-crop", 'mensagem' => "Item <strong>{$date['input_name']}</strong> successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item: {$data['input_name']}"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('productsCrop', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['name' => $itens->name, 'id' => $itens->id]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new ProductsCrop())->findById($data['id']);

        $item->name = $data['input_name'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products-crop", 'mensagem' => "Item <strong>{$date['input_name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['input_name']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new ProductsCrop())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products-crop", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
