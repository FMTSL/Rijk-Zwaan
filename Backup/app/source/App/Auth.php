<?php

namespace Source\App;

use Bcrypt\Bcrypt;
use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Users;

use function JBZoo\Data\json;

class Auth
{
    private $view;
    private $acoes;
    private $bcrypt;
    private $sessao;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->sessao = new Sessao();
        $this->bcrypt = new Bcrypt();
        $this->acoes = new Acoes();
    }

    public function login(): void
    {
        if ($this->sessao->getUser()) {
            redirect("/");
        } else {
            echo $this->view->render("pages/auth/main", [
                "title" => "Login | " . SITE,
                "sessao" => $this->sessao->getUser(),
            ]);
        }
    }

    public function authLogin($data): void
    {
        $users = $this->acoes->getByField('users', 'email', $data['email']);
        if ($users) {
            if ($this->bcrypt->verify($data['password'], $users->password)) {
                $inicia = $this->sessao->add($users->id, $users->email, $users->roles);
                $json = json_encode(['resp' => 1, 'redirect' => ROOT, 'mensagem' => "Access Validity! Please wait, we are redirecting you to the main page"]);
                //dd($json);
            } else {
                $json = json_encode(['resp' => 0, 'mensagem' => "Incorrect password. Make sure you typed it correctly!"]);
            }
        } else {
            $json = json_encode(['resp' => 0, 'mensagem' => "Email not found! Make sure you typed it correctly or contact your manager."]);
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function cadastro(): void
    {
        if ($this->sessao->getUser()) {
            redirect("/");
        } else {
            echo $this->view->render("pages/auth/registration", [
                "title" => "Cadastro | " . SITE,
                "sessao" => $this->sessao->getUser(),
            ]);
        }
    }

    public function authCadastro($data): void
    {
        $users = $this->acoes->getByField('users', 'email', $data['email']);
        if ($users) {
            $json = json_encode(['resp' => 0, 'mensagem' => "E-mail already registered! Login or request password recovery."]);
        } else {
            $senha = $this->bcrypt->encrypt($data['password'], '2a');

            $us = new Users();
            $us->name = $data['input_name'];
            $us->email = $data['email'];
            $us->phone = $data['phone'];
            $us->password = $senha;
            $us->roles = $data['roles'];
            $us->save();
            //dd($us);
            $json = $us->id > 0 ? json_encode(['resp' => 1, 'redirect' => ROOT . "/login", 'mensagem' => "User registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to register this user"]);
        }
        header('Content-Type: application/json');
        exit($json);
    }


    public function logout(): void
    {
        $this->sessao->logout();
    }
}
