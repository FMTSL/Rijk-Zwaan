<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\CustomerCategory;
use Source\Models\CustomerCategoryToCredit;

use function JBZoo\Data\json;

class AppCustomerCategory
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
        $itens = $this->acoes->getFind('customerCategory');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => !$item->internal_category_code ? "--" : $item->internal_category_code,
                    'basic_discount' => !$item->basic_discount ? "Customized" : "{$item->basic_discount}%",
                    'cash_payment_discount' => !$item->cash_payment_discount ? "Customized" : "{$item->cash_payment_discount}%",
                    'goal_discount' => !$item->goal_discount ? "Customized" : "Até {$item->goal_discount}% in seeds",
                    'goal_introduction' => !$item->goal_introduction ? "Customized" : "{$item->goal_introduction}% in seeds",
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
        $count = $this->acoes->countAdd('customerCategory');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('customerCategory', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/customerCategory/main", [
                "title" => "Customer Category",
                "description" => "Create categories here to define the profile of each customer",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'pager' => $pager->render('p-3 page'),
                'deadline' => $this->acoes->getFind('customerCreditDeadline'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {

        $itens = $this->acoes->getByField('customerCategory', 'name', $data['input_name']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "A category with this name already exists! Try a new one."]);
        } else {

            $item = new CustomerCategory();
            $item->name = $data['input_name'];
            $item->internal_category_code = $data['code'];
            $item->basic_discount = !$data['basic_discount'] ? 0 : $data['basic_discount'];
            $item->cash_payment_discount = !$data['cash_payment_discount'] ? 0 : $data['cash_payment_discount'];
            $item->goal_discount = !$data['goal_discount'] ? 0 : $data['goal_discount'];
            $item->goal_introduction = !$data['goal_introduction'] ? 0 : $data['goal_introduction'];
            $item->save();
            //dd($item);
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customer-categories", 'mensagem' => "Category registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the Category"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function newActionDeadline($data): void
    {
        $item = new CustomerCategoryToCredit();

        $item->id_category = $data['id_category'];
        $item->id_credit_deadline = $data['id_credit_deadline'];
        $item->save();
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Item adicionado com sucesso"]) : json_encode(['resp' => 0, 'mensagem' => "Não foi possível adicionado o Item"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function listViewDeadline($data): void
    {
        $itens = $this->acoes->getByFieldAll('customerCategoryToCredit', 'id_category', $data['id']);
        foreach ($itens as &$item) {
            $its[] = $item->id_credit_deadline;
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('customerCategory', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode([
            'name' => $itens->name,
            'code' => $itens->internal_category_code,
            'basic_discount' => $itens->basic_discount,
            'cash_payment_discount' => $itens->cash_payment_discount,
            'goal_discount' => $itens->goal_discount,
            'goal_introduction' => $itens->goal_introduction,
            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new CustomerCategory())->findById($data['id']);
        $item->name = $data['input_name'];
        $item->internal_category_code = $data['code'];
        $item->basic_discount = $data['basic_discount'];
        $item->cash_payment_discount = $data['cash_payment_discount'];
        $item->goal_discount = $data['goal_discount'];
        $item->goal_introduction = $data['goal_introduction'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customer-categories", 'mensagem' => "Category updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update the Category"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new CustomerCategory())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customer-categories", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }


    public function deleteActionDeadline($data): void
    {
        $itens = $this->acoes->getByFieldTwo('customerCategoryToCredit', 'id_category', $data['id_category'], 'id_credit_deadline', $data['id_credit_deadline']);
        $item = (new CustomerCategoryToCredit())->findById($itens->id);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Item removed successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not remove selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
