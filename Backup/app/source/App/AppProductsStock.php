<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\ProductsStock;

use function JBZoo\Data\json;

class AppProductsStock
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
            echo $this->view->render("pages/productsStock/main", [
                "title" => 'Products',
                "description" => "List of all products registered in our system",
                "userRoles" => $this->sessao->getRoles(),
                'package' => $this->acoes->getFind('productsPackaging'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'productsCrop' => $this->acoes->getFind('productsCrop'),
                "itens" => $this->acoes->getFind('productsStock'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewId($data): void
    {
        $itens = $this->acoes->getByFieldAllTwo('productsStock', 'id_variety', $data['id'], 'status', 'true');
        if ($itens) {
            foreach ($itens as &$item) {
                $packaging = $this->acoes->getByField('productsPackaging', 'id', $item->id_package);
                $its[$packaging->id] = [
                    'id' => $item->id,
                    'id_package' => $item->id_package,
                    'package' => $packaging->name,
                    'value' => $item->value,
                    'weight' => $item->weight,
                    'id_variety' => $packaging->id_variety,
                    'status' => $packaging->status,
                    'quantity' => $packaging->quantity,
                    'article_number' => $packaging->article_number,
                    'sub_article_number' => $packaging->sub_article_number,
                ];
            }
        } else {
            $its = 0;
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('productsStock');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'value' => number_format($item->value, 2, ',', '.'),
                    'weight' => number_format($item->weight, 2, '.', '.'),
                    'id_package' => $this->acoes->getData('productsPackaging', $item->id_package, 'name'),
                    'id_variety' => $this->acoes->getData('productsVariety', $item->id_variety, 'name'),
                    'sub_article_number' => $item->sub_article_number,
                    'article_number' => $item->article_number,
                    'status' => $item->status,
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
        $item = new ProductsStock();
        $item->quantity = $data['quantity'];
        $item->id_package = $data['id_package'];
        $item->sub_article_number = $data['sub_article_number'];
        $item->id_variety = $data['id_variety'];
        $item->article_number = $data['article_number'];
        $item->value = $data['value'];
        $item->weight = $data['weight'];
        $item->value = $data['value'];
        $item->status = true;
        $item->save();
        //dd($item);
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/product/stock", 'mensagem' => "Item successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('productsStock', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'quantity' => $itens->quantity,
            'weight' => $itens->weight,
            'id_package' => $itens->id_package,
            'sub_article_number' => $itens->sub_article_number,
            'id_variety' => $itens->id_variety,
            'article_number' => $itens->article_number,
            'value' => $itens->value,
            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new ProductsStock())->findById($data['id']);
        $item->quantity = $data['quantity'];
        $item->id_package = $data['id_package'];
        //$item->sub_article_number = $data['sub_article_number'];
        $item->id_variety = $data['id_variety'];
        $item->article_number = $data['article_number'];
        $item->value = $data['value'];
        $item->weight = $data['weight'];
        $item->value = $data['value'];
        $item->status = true;
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/product/stock", 'mensagem' => "Item updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateQtdAction($id, $quantity)
    {
        $item = (new ProductsStock())->findById($id);
        $item->quantity = $quantity;
        $item->save();
    }

    public function deleteAction($data)
    {
        $item = (new ProductsStock())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/product/stock", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}