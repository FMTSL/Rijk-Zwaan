<?php

namespace Source\App;

use Bcrypt\Bcrypt;
use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Users;

use function JBZoo\Data\json;

class AppUser
{
    private $view;
    private $acoes;
    private $sessao;
    private $bcrypt;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->sessao = new Sessao();
        $this->bcrypt = new Bcrypt();
        $this->acoes = new Acoes();
    }

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('users');
        if ($itens) {
            foreach ($itens as &$item) {
                switch ($item->roles) {
                    case 0:
                        $roles = "Super Administrator";
                        break;
                    case 1:
                        $roles = "Administrator";
                        break;
                    case 2:
                        $roles = "Salesman";
                        break;
                    case 3:
                        $roles = "Customer";
                        break;
                    case 4:
                        $roles = "Client Service";
                        break;
                }

                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'email' => $item->email,
                    'phone' => $item->phone,
                    'password' => $item->password,
                    'roles' => $roles,
                    'actions' => '<button class="btn btn-success mr-2" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button> <button onclick="deletar(' . $item->id . ')" class="btn btn-danger"><i class="fa fa-times"></i></button>',
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
        $count = $this->acoes->countAdd('users');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('users', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/users/main", [
                "title" => "Users",
                "description" => "Users registered in the system",
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
        $itens = $this->acoes->getByField('users', 'email', $data['email']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "There is already a user with this email! Try a new one."]);
        } else {

            $senha = $this->bcrypt->encrypt($data['password'], '2a');

            $item = new Users();
            $item->name = $data['input_name'];
            $item->email = $data['email'];
            $item->phone = $data['phone'];
            $item->password = $senha;
            $item->roles = $data['roles'];
            $item->save();
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/users", 'mensagem' => "Successfully registered user"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to register the user"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('users', 'id', $data['id']);
        header('Content-Type: application/json');
        $json = json_encode(['name' => $itens->name, 'email' => $itens->email, 'phone' => $itens->phone, 'password' => $itens->password, 'roles' => $itens->roles, 'id' => $itens->id]);
        exit($json);
    }

    public function updateAction($data): void
    {

        if ($data['password']) {
            $senha = $this->bcrypt->encrypt($data['password'], '2a');
        }

        $item = (new Users())->findById($data['id']);
        $item->name = $data['input_name'];
        $item->email = $data['email'];
        $item->phone = $data['phone'];
        if ($data['password']) {
            $item->password = $senha;
        }
        $data['roles'] == "Select" ?: $item->roles = $data['roles'];
        $item->save();
        //dd($item);
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/users", 'mensagem' => "Item <strong>{$date['name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['name']}"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new Users())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/users", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
