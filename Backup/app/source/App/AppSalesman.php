<?php

namespace Source\App;

use Bcrypt\Bcrypt;
use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Users;
use Source\Models\Salesman;

use function JBZoo\Data\json;

class AppSalesman
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
        $itens = $this->acoes->getFind('salesman');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'full_name' => $item->full_name,
                    'genre' => $item->genre,
                    'birthdate' => date("d/m/Y", $item->birthdate),
                    'id_user' =>  $this->acoes->getData('users', $item->id_user, 'email'),
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
        $count = $this->acoes->countAdd('salesman');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('salesman', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/salesman/main", [
                "title" => "Sales Representative",
                "description" => "Salesman registered in the system",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                "users" => $this->acoes->getByField('users', 'roles', 2),
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

            if ($item->id > 0) {
                $sales = new Salesman();
                $sales->name = $data['input_name'];
                $sales->full_name = $data['full_name'];
                $sales->genre = $data['genre'];
                $sales->birthdate = $data['birthdate'];
                $sales->id_user = $item->id;
                $sales->save();

                $json = $sales->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/salesman", 'mensagem' => "Successfully registered user"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to register the user"]);
            }
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function newUserAction($data): void
    {
        $itens = $this->acoes->getByField('salesman', 'id', $data['salesman_id']);
        $senha = $this->bcrypt->encrypt($data['salesman_password'], '2a');
        $item = new Users();
        $item->name = $data['salesman_input_name'];
        $item->email = $data['salesman_email'];
        $item->phone = $data['salesman_phone'];
        $item->password = $senha;
        $item->roles = 2;
        $item->save();

        if ($item->id > 0) {
            $sales = new Salesman();
            $sales->name = $data['salesman_input_name'];
            $sales->id_user = $item->id;
            $sales->save();
            $json = $sales->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/salesman", 'mensagem' => "Successfully registered user"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to register the user"]);
        }

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $salesman = $this->acoes->getByField('salesman', 'id', $data['id']);

        if ($salesman->id_user) {
            $users = $this->acoes->getByField('users', 'id', $salesman->id_user);

            header('Content-Type: application/json');
            $json = json_encode([
                'name' => $salesman->name,
                'full_name' => $salesman->full_name,
                'genre' => $salesman->genre,
                'birthdate' => $salesman->birthdate,
                'id_user' => $salesman->id_user,
                'salesman_id' => $salesman->id,

                'email' => $users->email,
                'phone' => $users->phone,
                'password' => $users->password,
                'user_id' => $users->id,
            ]);
        } else {
            header('Content-Type: application/json');
            $json = json_encode([
                'name' => $salesman->name,
                'full_name' => $salesman->full_name,
                'genre' => $salesman->genre,
                'birthdate' => $salesman->birthdate,
                'id_user' => $salesman->id_user,
                'salesman_id' => $salesman->id,
            ]);
        }
        exit($json);
    }

    public function updateAction($data): void
    {
        if ($data['password']) {
            $senha = $this->bcrypt->encrypt($data['password'], '2a');
        }

        $salesman = $this->acoes->getByField('salesman', 'id', $data['id']);
        $birthdate = date('Y-m-d H:i:s', strtotime($data['birthdate-date']));

        $sales = (new Salesman())->findById($data['id']);
        $sales->name = $data['input_name'];
        $sales->full_name = $data['full_name'];
        $sales->genre = $data['genre'];
        $sales->birthdate = $birthdate;
        $sales->save();

        if ($sales->id > 0) {

            if ($salesman->id_user > 0) {
                $item = (new Users())->findById($salesman->id_user);
                $item->name = $data['input_name'];
                $item->email = $data['email'];
                $item->phone = $data['phone'];
                if ($data['password']) {
                    $item->password = $senha;
                }
                $item->save();
            } else {
                $item = new Users();
                $item->name = $data['input_name'];
                $item->email = $data['email'];
                $item->phone = $data['phone'];
                $item->roles = 2;
                $item->password = $senha;
                $item->save();

                $salesUser = (new Salesman())->findById($data['id']);
                $salesUser->id_user = $item->id;
                $sales->save();
            }

            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/salesman", 'mensagem' => "Item <strong>{$date['input_name']}</strong> updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item: {$date['input_name']}"]);
            header('Content-Type: application/json');
            exit($json);
        }
    }

    public function deleteAction($data)
    {
        $salesman = $this->acoes->getByField('salesman', 'id', $data['id']);

        $item = (new Users())->findById($salesman->id_user);
        $item->destroy();

        $sales = (new Salesman())->findById($data['id']);
        $sales->destroy();

        $json = $sales->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/salesman", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
