<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;
use Source\Models\Files;

class AppFiles
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
        $itens = $this->acoes->getFind('files');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $itens->name,
                    'fileName' => $itens->file_name,
                    'actions' => '<a class="btn btn-info" href="' . URL_BASE . '/uploads/' . $item->file_name . '"><i class="fa fa-box-open"></i></a><button class="btn btn-success" onclick="deletar(' . $item->id . ')"><i class="fa fa-close"></i></button>',
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
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/files/main", [
                "title" => "Uploads Files",
                "description" => "Uploads Files",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewNew(): void
    {
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/files/file", [
                "title" => "Uploads Files",
                "description" => "Uploads Files",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {


        $upload = new \CoffeeCode\Uploader\File(URL_BASE . "/uploads", "pdf");
        $files = $_FILES;
        if (!empty($files["pdf"])) {
            $file = $files["pdf"];
            try {
                $uploaded = $upload->upload($file, $file["name"]);

                $prod = new Files();
                $prod->name = $data['input_name'];
                $prod->file_name = $uploaded;
                $prod->save();

                $json = $prod->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/import/products", 'mensagem' => "Import completed successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to import"]);
                header('Content-Type: application/json');
                exit($json);
            } catch (\Exception $e) {
                echo "<p>(!) {$e->getMessage()}</p>";
            }
        }
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('uploads', 'id', $data['id']);

        header('Content-Type: application/json');
        $json = json_encode([
            'name' => $itens->name,
            'id' => $itens->id,
            'fileName' => $itens->file_name,
        ]);
        exit($json);
    }



    public function deleteAction($data)
    {
        $item = (new Files())->findById($data['id']);

        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
