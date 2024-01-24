<?php

namespace Source\App;

use Bcrypt\Bcrypt;
use Google\Service\TrafficDirectorService\ClientStatusRequest;
use http\Client;
use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Address;
use Source\Models\Customers;
use Source\Models\Users;
use Source\Classe\GeneratePassword;
use Source\Models\RelationshipCustomerAddress;

use function JBZoo\Data\json;

class AppCustomers
{

    private $view;
    private $acoes;
    private $sessao;
    private $bcrypt;
    private $generatePassword;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->sessao = new Sessao();
        $this->bcrypt = new Bcrypt();
        $this->generatePassword = new GeneratePassword();
        $this->acoes = new Acoes();
    }

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('customers');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'full_name' => $item->full_name,
                    'email' => $item->email,
                    'mobile' => $item->mobile,
                    'cnpj' => $item->cnpj,
                    'special_client' => $item->special_client == 1 ? 'Yes' : 'No',

                    'actions' => $this->sessao->getRoles() != 2 ? '<a class="btn btn-success" href="' . ROOT . '/customer/' . $item->id . '"><i class="fa fa-edit"></i></a> <button onclick="deletar(' . $item->id . ')" class="btn btn-danger"><i class="fa fa-times"></i></button>' : '',
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
        $count = $this->acoes->countAdd('customers');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('customers', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/customers/main", [
                "title" => "Customers",
                "description" => "Customers registered in the system",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'users' => $this->acoes->getByFieldAll('users', 'roles', 2),
                'salesman' => $this->acoes->getFind('salesman'),
                'categoryCustomerType' => $this->acoes->getFind('customerType'),
                "itens" => $itens,
                'category' => $this->acoes->getFind('customerCategory'),
                'countrys' => $this->acoes->getFind('addressCountry'),
                'states' => $this->acoes->getFind('addressState'),
                'pager' => $pager->render('p-3 page'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {
        $itens = $this->acoes->getByField('customers', 'email', $data['email']);
        if ($itens) {
            $json = json_encode(['resp' => 0, 'mensagem' => "There is already a customer with this email! try a new one."]);
        } else {
            $addr = new Address();
            $addr->address_1 = $data['address_1'];
            $addr->address_2 = $data['address_2'];
            $addr->zipcode = $data['zipcode'];
            $addr->city = $data['city'];
            $addr->id_state = $data['id_state'];
            $addr->id_country = $data['id_country'];
            $addr->save();


            if ($addr->id > 0) {
                $senha = $this->bcrypt->encrypt($this->generatePassword->generate(10, true, true, true, true), '2a');

                $item = new Users();
                $item->name = $data['full_name'];
                $item->email = $data['email'];
                $item->phone = $data['telephone'];
                $item->password = $senha;
                $item->roles = 3;
                $item->save();



                if ($item->id > 0) {
                    $cli = new Customers();
                    $cli->id_salesman = $data['id_salesman'];
                    $cli->id_category_customer = $data['id_category_customer'];
                    $cli->id_category = $data['id_category'];
                    $cli->full_name = $data['full_name'];
                    $cli->email = $data['email'];
                    $cli->telephone = $data['telephone'];
                    $cli->fax = $data['fax'];
                    $cli->mobile = $data['mobile'];
                    $cli->website = $data['website'];
                    $cli->relation_number = $data['relation_number'];
                    $cli->bio = $data['bio'];
                    $cli->id_customer = $item->id;
                    $cli->cnpj = $data['cnpj'];
                    $cli->special_client = $data['special_client'];
                    $cli->save();

                    //dd($cli);

                    if ($cli->id > 0) {
                        $addRela = new RelationshipCustomerAddress();
                        $addRela->id_address = $addr->id;
                        $addRela->id_customer = $cli->id;
                        $addRela->delivery_type = 1;
                        $addRela->save();

                        //dd($addRela);
                        $json = $addRela->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customers", 'mensagem' => "Successfully registered user"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to register the user"]);
                    }
                }
            }
        }
        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['id']);
        //dd($this->acoes->getByFieldAll('relationshipClientAddress','id_customer', $client->id));
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/customers/update", [
                "title" => "Customers Update",
                "description" => "Customers registered in the system",
                'users' => $this->acoes->getByField('users', 'id', $client->id_customer),
                'salesman1' => $this->acoes->getByFieldAll('users', 'roles', 2),
                'client' => $client,
                'relationshipClientAddress' => $this->acoes->getByFieldAll('relationshipCustomerAddress', 'id_customer', $client->id),
                'address' => $this->acoes->getFind('address'),
                'categoryCustomerType' => $this->acoes->getFind('customerType'),
                'salesman' => $this->acoes->getFind('salesman'),
                'categoryCustomer' => $this->acoes->getFind('customerCategory'),
                'state' => $this->acoes->getFind('addressState'),
                'country' => $this->acoes->getFind('addressCountry')
            ]);
        } else {
            redirect("/login");
        }
    }

    public function updateAction($data): void
    {
        $cli = (new Customers())->findById($data['id']);
        $cli->id_salesman = $data['id_salesman'];
        $cli->id_category_customer = $data['id_category_customer'];
        $cli->full_name = $data['full_name'];
        $cli->email = $data['email'];
        $cli->telephone = $data['telephone'];
        $cli->fax = $data['fax'];
        $cli->mobile = $data['mobile'];
        $cli->website = $data['website'];
        $cli->relation_number = $data['relation_number'];
        $cli->id_category = $data['id_category'];
        $cli->bio = $data['bio'];
        $cli->cnpj = $data['cnpj'];
        $cli->special_client = $data['special_client'];
        $cli->save();

        if ($cli->id > 0) {
            $item = (new Users())->findById($cli->id_customer);
            $item->name = $data['full_name'];
            $item->email = $data['email'];
            $item->phone = $data['telephone'];
            $item->save();

            if ($item->id > 0) {
                $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Item updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item!"]);
                header('Content-Type: application/json');
                exit($json);
            }
        }
    }

    public function deleteAction($data)
    {
        $item = (new Customers())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customers", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}