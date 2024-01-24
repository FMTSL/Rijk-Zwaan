<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\CustomerType;

use function JBZoo\Data\json;

class AppCustomerType
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
        $itens = $this->acoes->getFind('customerType');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'actions' => '<button class="btn btn-success" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button> <button onclick="deletar(' . $item->id . ')" class="btn btn-danger"><i class="fa fa-times"></i></button>',
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
        $count = $this->acoes->countAdd('customerType');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('customerType', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/customerType/main", [
                "title" => "Customer Type",
                "description" => "Customer type",
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
        $itens = $this->acoes->getByField('customerType', 'name', $data['input_name']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "There is already a customer type with this name! Try a new one."]);
        } else {

            $item = new CustomerType();

            $item->name = $data['input_name'];
            $item->slug = $data['slug'];
            $item->save();
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customer-type", 'mensagem' => "Category successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the customer type"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('customerType', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['name' => $itens->name, 'slug' => $itens->slug, 'id' => $itens->id]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new CustomerType())->findById($data['id']);
        $item->name = $data['input_name'];
        $item->slug = $data['slug'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customer-type", 'mensagem' => "Customer type updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update the customer type"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new CustomerType())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customer-type", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
