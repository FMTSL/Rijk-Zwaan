<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;

class Web
{
    private $view;
    private $acoes;
    private $sessao;
    private $connect;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->sessao = new Sessao();
        $this->acoes = new Acoes();
    }

    public function home(): void
    {
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/dashboard/main", [
                "title" => "Home | " . SITE,
                "sessao" => $this->sessao->getUser(),
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
            ]);
        } else {
            redirect("/login");
        }
    }
    public function contact(): void
    {
        echo "<h1>Contato</h1>";
    }
    public function error(array $data): void
    {
        echo $this->view->render("error", [
            "title" => "Erro {$data["errcode"]} | " . SITE,
            "error" => $data["errcode"],
        ]);
    }
}
