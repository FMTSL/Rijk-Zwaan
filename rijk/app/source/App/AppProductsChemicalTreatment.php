<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\ProductsChemicalTreatment;

use function JBZoo\Data\json;

class AppProductsChemicalTreatment
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
        $itens = $this->acoes->getFind('productsChemicalTreatment');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
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
        $count = $this->acoes->countAdd('productsChemicalTreatment');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('productsChemicalTreatment', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/chemicalTreatment/main", [
                "title" => "Chemical treatment",
                "description" => "Chemical treatment of the product",
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
        $itens = $this->acoes->getByField('productsChemicalTreatment', 'name', $data['input_name']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "There is already a chemical treatment with this name! Try a new one."]);
        } else {

            $item = new ProductsChemicalTreatment();

            $item->name = $data['input_name'];
            $item->save();
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/chemical-treatment", 'mensagem' => "Chemical treatment successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the chemical treatment"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('productsChemicalTreatment', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['name' => $itens->name, 'slug' => $itens->slug, 'id' => $itens->id]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new ProductsChemicalTreatment())->findById($data['id']);
        $item->name = $data['input_name'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/chemical-treatment", 'mensagem' => "Chemical treatment successfully updated"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to update the Chemical Treatment"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new ProductsChemicalTreatment())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/chemical-treatment", 'mensagem' => "Chemical treatment successfully deleted"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
