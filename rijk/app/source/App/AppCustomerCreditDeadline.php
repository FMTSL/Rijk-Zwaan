<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Category;
use Source\Models\CustomerCreditDeadline;

use function JBZoo\Data\json;

class AppCustomerCreditDeadline
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
        $itens = $this->acoes->getFind('customerCreditDeadline');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'deadline' => $item->deadline,
                    'type' => $item->type == 0 ? "< " . number_format($item->value, 2, ',', '.') : "> " . number_format($item->value, 2, ',', '.'),
                    'id_customer_category' => $this->acoes->getData('customerCategory', $item->id_customer_category, 'name'),
                    'type_payment' => $this->acoes->getData('customerPaymentType', $item->type_payment, 'name'),
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
        $count = $this->acoes->countAdd('customerCreditDeadline');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('customerCreditDeadline', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/customerCreditDeadline/main", [
                "title" => "Payment conditions",
                "description" => "Registered Payment conditions",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'pager' => $pager->render('p-3 page'),
                'categoryCustomer' => $this->acoes->getFind('customerCategory'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {
        $item = new CustomerCreditDeadline();
        $item->value = $data['valor'];
        $item->deadline = $data['deadline'];
        $item->type = $data['type'];
        $item->id_customer_category = $data['id_customer_category'];
        $item->type_payment = $data['type_payment'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/credit-term", 'mensagem' => "Item registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the Item"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('customerCreditDeadline', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'value' => $itens->value,
            'deadline' => $itens->deadline,
            'type_payment' => $itens->type_payment,
            'id_customer_category' => $itens->id_customer_category,
            'type' => $itens->type,
            'id' => $itens->id
        ]);
        exit($json);
    }


    public function listViewId($data): void
    {
        //dd($data);
        $itens = $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $data['id_customer_category'], 'type_payment', $data['payment_type']);

        //dd($itens);

        foreach ($itens as &$item) {
            $its[] = [
                'id' => $item->id,
                'deadline' => $item->deadline,
                'type_payment' => $item->type_payment,
                'id_customer_category' => $item->id_customer_category,
                'type' => $item->type
            ];
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new CustomerCreditDeadline())->findById($data['id']);
        $item->value = $data['valor'];
        $item->deadline = $data['deadline'];
        $item->type = $data['type'];
        $item->id_customer_category = $data['id_customer_category'];
        $item->type_payment = $data['type_payment'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/credit-term", 'mensagem' => "Item updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new CustomerCreditDeadline())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/credit-term", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
