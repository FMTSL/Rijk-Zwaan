<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\productsStockClone;

use function JBZoo\Data\json;

class AppproductsStockClone
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
            echo $this->view->render("pages/productsStock/mainClone", [
                "title" => 'Products',
                "description" => "List of all products registered in our system",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'productsCrop' => $this->acoes->getFind('productsCrop'),
                "itens" => $this->acoes->getFind('productsStockClone'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewId($data): void
    {
        $itens = $this->acoes->getByFieldAll('productsStockClone', 'id_variety', $data['id']);
        if ($itens) {
            foreach ($itens as &$item) {
                $packaging = $this->acoes->getByField('productsPackaging', 'id', $item->id_package);
                $its[$packaging->id] = [
                    'id' => $item->id,
                    'id_package' => $item->id_package,
                    'package' => $packaging->name,
                    'value' => $item->value,
                    'weight' => $item->weight,
                    'id_variety' => $item->id_variety,
                    'status' => $packaging->status,
                    'quantity' => $packaging->quantity,
                    'article_number' => $packaging->article_number,
                    'id_crop' => $item->id_crop,
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
        $itens = $this->acoes->getFind('productsStockClone');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'id_crop' => $item->id_crop,
                    'id_variety' => $item->id_variety,
                    'batch' => $item->batch,
                    'product_sales_unit' => $item->product_sales_unit,
                    'one_of_sale' => $item->one_of_sale,
                    'packaging_expiration' => $item->packaging_expiration,
                    'treatments' => $item->treatments,
                    'sum_of_qty_in_vwh_local' => $item->sum_of_qty_in_vwh_local,
                    'actions' => '<button onclick="deletar(' . $item->id . ')" class="btn btn-danger"><span class="fa fa-times"></span></button>',
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
        $item = new ProductsStockClone();
        $item->quantity = $data['quantity'];
        $item->id_package = $data['id_package'];
        $item->id_crop = $data['id_crop'];
        $item->id_variety = $data['id_variety'];
        $item->article_number = $data['article_number'];
        $item->value = $data['value'];
        $item->weight = $data['weight'];
        $item->value = $data['value'];
        $item->status = true;
        $item->save();
        //dd($item);
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/product/stock/clone", 'mensagem' => "Item successfully registered"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the item"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('productsStockClone', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'quantity' => $itens->quantity,
            'weight' => $itens->weight,
            'id_package' => $itens->id_package,
            'id_crop' => $itens->id_crop,
            'id_variety' => $itens->id_variety,
            'article_number' => $itens->article_number,
            'value' => $itens->value,
            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new ProductsStockClone())->findById($data['id']);
        $item->quantity = $data['quantity'];
        $item->id_package = $data['id_package'];
        $item->id_crop = $data['id_crop'];
        $item->id_variety = $data['id_variety'];
        $item->article_number = $data['article_number'];
        $item->value = $data['value'];
        $item->weight = $data['weight'];
        $item->value = $data['value'];
        $item->status = true;
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/product/stock/clone", 'mensagem' => "Item updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateQtdAction($id, $quantity)
    {
        //dd($id + $quantity);
        $item = (new ProductsStockClone())->findById($id);
        $item->quantity = $quantity;
        $item->save();
    }

    public function deleteAction($data)
    {
        $item = (new ProductsStockClone())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/product/stock/clone", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAllAction()
    {
        $itens = $this->acoes->getFind('productsStockClone');
        if ($itens) {
            foreach ($itens as &$item) {
                $item = (new ProductsStockClone())->findById($item->id);
                $item->destroy();
            }
            $json = json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/import/products/clone", 'mensagem' => "All items have been successfully deleted."]);
            header('Content-Type: application/json');
            exit($json);
        }
    }
}