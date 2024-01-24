<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\Address;
use Source\Models\RelationshipCustomerAddress;

use function JBZoo\Data\json;

class AppAddress
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
        $itens = $this->acoes->getFind('products');
        if ($itens) {
            foreach ($itens as &$item) {
                $its[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'id_crop' => $this->acoes->getData('productsCrop', $item->id_crop, 'name'),
                    'id_variety' => $this->acoes->getData('productsVariety', $item->id_variety, 'name'),
                    'id_sales_unit' => $this->acoes->getData('productsSalesUnit', $item->id_sales_unit, 'type'),
                    'id_chemical_treatment' => $this->acoes->getData('productsChemicalTreatment', $item->id_chemical_treatment, 'name'),
                    'batch' => $item->batch,
                    'maturity' => $item->maturity,
                    'actions' => '<a class="btn btn-info" href="' . ROOT . '/product/stock/item/' . $item->id . '"><i class="fa fa-box-open"></i></a><button class="btn btn-success" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button>',
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
        $count = $this->acoes->countAdd('address');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('address', $pager->limit(), $pager->offset(), 'id ASC');

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/address/main", [
                "title" => "Adresses",
                "description" => "Customer addresses",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'pager' => $pager->render('p-3 page'),
                'state' => $this->acoes->getFind('addressState'),
                'country' => $this->acoes->getFind('addressCountry')
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newAction($data): void
    {
        $item = new Address();
        $item->type = $data['type'];
        $item->address_1 = $data['address_1'];
        $item->address_2 = $data['address_2'];
        $item->zipcode = $data['zipcode'];
        $item->city = $data['city'];
        $item->id_state = $data['id_state'];
        $item->id_country = $data['id_country'];
        $item->save();

        if ($item->id > 0) {
            $endMaster = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'id_customer', $data['id_customer'], 'delivery_type', 1);

            /* if ($data['delivery_type'] == 1) {
                if ($endMaster) {
                    $relCustomerAddressUp = (new RelationshipCustomerAddress())->findById($endMaster->id);
                    $relCustomerAddressUp->delivery_type = 2;
                    $relCustomerAddressUp->save();
                }
            } */

            $relCustomerAddressNew = new RelationshipCustomerAddress();
            $relCustomerAddressNew->id_address = $item->id;
            $relCustomerAddressNew->id_customer = $data['id_customer'];
            $relCustomerAddressNew->delivery_type = $data['delivery_type'];
            $relCustomerAddressNew->save();
        }

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/adresses", 'mensagem' => "Addresses registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the addresses"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('address', 'id', $data['id']);
        $relItens = $this->acoes->getByField('relationshipCustomerAddress', 'id_address', $itens->id);

        header('Content-Type: application/json');
        $json = json_encode([
            'type' => $itens->type,
            'address_1' => $itens->address_1,
            'address_2' => $itens->address_2,
            'zipcode' => $itens->zipcode,
            'city' => $itens->city,
            'id_state' => $itens->id_state,
            'id_country' => $itens->id_country,
            'id_country' => $itens->id_country,

            'id_relationship_customer_address' => $relItens->id,
            'id_address' => $relItens->id_address,
            'id_customer' => $relItens->id_customer,
            'delivery_type' => $relItens->delivery_type,

            'id' => $itens->id
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {
        $item = (new Address())->findById($data['id_address']);
        $item->type = $data['type'];
        $item->address_1 = $data['address_1'];
        $item->address_2 = $data['address_2'];
        $item->zipcode = $data['zipcode'];
        $item->city = $data['city'];
        $item->id_state = $data['id_state'];
        $item->id_country = $data['id_country'];
        $item->save();

        if ($item->id > 0) {
            $endActualy = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'id_customer', $data['id_customer'], 'id_address', $data['id_address']);
            if ($data['delivery_type'] == 1) {
                $endMaster = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'id_customer', $data['id_customer'], 'delivery_type', 1);
                if ($endMaster) {
                    if ($endActualy->id == $endMaster->id) {
                        $relCustomerAddressNew = (new RelationshipCustomerAddress())->findById($endActualy->id);
                        $relCustomerAddressNew->delivery_type = $data['delivery_type'];
                        $relCustomerAddressNew->save();
                    } else {
                        $relCustomerAddressUp = (new RelationshipCustomerAddress())->findById($endMaster->id);
                        $relCustomerAddressUp->delivery_type = 2;
                        $relCustomerAddressUp->save();

                        $relCustomerAddressNew = (new RelationshipCustomerAddress())->findById($endActualy->id);
                        $relCustomerAddressNew->delivery_type = 1;
                        $relCustomerAddressNew->save();
                    }
                } else {
                    $relCustomerAddressNew = (new RelationshipCustomerAddress())->findById($endActualy->id);
                    $relCustomerAddressNew->delivery_type = $data['delivery_type'];
                    $relCustomerAddressNew->save();
                }
            }

            $relCustomerAddressNew = (new RelationshipCustomerAddress())->findById($endActualy->id);
            $relCustomerAddressNew->delivery_type = $data['delivery_type'];
            $relCustomerAddressNew->save();
        }


        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/adresses", 'mensagem' => "Addresses updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update addresses"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new Address())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/adresses", 'mensagem' => "Address successfully deleted"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}
