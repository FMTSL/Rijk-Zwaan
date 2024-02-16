<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;
use Source\Classe\Correios;
use Source\Models\OrderCart;
use Source\Models\Orders;
use Source\Models\ProductsStock;
use Source\Models\RelationshipStockCartOrder;
use Source\Models\Products;


use function JBZoo\Data\json;

class AppOrderForm
{
    private $view;
    private $acoes;
    private $sessao;
    private $correios;
    private $coProductsStock;
    private $coProductsRelationshipStock;


    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->correios = new Correios();
        $this->sessao = new Sessao();

        $this->acoes = new Acoes();
        $this->coProductsStock = new AppProductsStock();
        $this->coProductsRelationshipStock = new AppRelationshipStockCartOrder();
    }

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('orders');
        if ($itens) {
            foreach ($itens as &$item) {
                if ($item->status > 0) {
                    if ($item->status == 1 || $item->status == 2 || $item->status == 3) {
                        $status = '<a class="btn btn-slin btn-success" href="/order-to-order/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '" ><i class="fa fa-edit"></i></a> <a class="btn btn-slin btn-warning" onclick="gerarPDF(' . $item->id . ',' . $item->id_customer . ',' . $item->order_number . ')" href="#"><i class="fa fa-print"></i></a> <a class="btn btn-slin btn-info" href="' . ROOT . '/order-to-orders/logistics/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '"><i class="fa fa-eye"></i></a>';
                    } else if ($item->status == 12) {
                        $status = ' <a class="btn btn-slin btn-danger" href="' . ROOT . '/order-to-orders/logistics/pdf-generate/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '" target="_blank"><i class="fa fa-file-pdf"></i></a> <a class="btn btn-slin btn-info" href="' . ROOT . '/order-to-orders/logistics/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '"><i class="fa fa-eye"></i></a>';
                    } else {
                        $status = ' <a class="btn btn-slin btn-warning" onclick="gerarPDF(' . $item->id . ',' . $item->id_customer . ',' . $item->order_number . ')" href="#"><i class="fa fa-print"></i></a> <a class="btn btn-slin btn-info" href="' . ROOT . '/order-to-orders/logistics/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '"><i class="fa fa-eye"></i></a>';
                    }
                    $its[] = [
                        'id' => $item->id,
                        'order_number' => $item->order_number,
                        'bonus_order' => $item->bonus_order,
                        'order_date' => date('d/m/Y', strtotime($item->order_date)),
                        'created_at' => date('d/m/Y', strtotime($item->created_at)),
                        'hora' => date('H:i', strtotime($item->created_at)),
                        'id_customer' => $this->acoes->getData('customers', $item->id_customer, 'full_name'),
                        'category' => $this->acoes->getData('customerCategory', $this->acoes->getData('customers', $item->id_customer, 'id_category_customer'), 'name'),
                        'id_bonus_order' => $item->bonus_order == 1 ? $this->acoes->getData('bonusOrder', $item->id_bonus_order, 'discount') . '%' : ((floatval($item->discount) + floatval($item->additional_discount)) + floatval($item->cash_payment)) . '%',
                        'status' => $this->acoes->getData('assistStatus', $item->status, 'name') . "<hr style='background: " . $this->acoes->getData('assistStatus', $item->status, 'color') . "; height: 5px; width: 100%;opacity: 1;' />",
                        'id_salesman' => $this->acoes->getData('salesman',  $this->acoes->getData('customers', $item->id_customer, 'id_salesman'), 'name'),
                        'value_total' => $item->bonus_order == 1 ? '--' : 'R$ ' . number_format($item->value_total, 2, ',', '.'),
                        'value' => $item->bonus_order == 1 ? '--' : 'R$ ' . number_format($item->value, 2, ',', '.'),
                        'actions' => $status,
                    ];
                }
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
        $count = $this->acoes->countAdd('orders');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('orders', $pager->limit(), $pager->offset(), 'id DESC');
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/orderForm/main", [
                "title" => "Order",
                "description" => "List of all orders in the app",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'pager' => $pager->render('p-3 page'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'users' => $this->acoes->getFind('users'),
                'customers' => $this->acoes->getFind('customers'),
                'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'customerCategory' => $this->acoes->getFind('customerCategory'),
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'stock' => $this->acoes->getFind('productsStock'),
                'status' => $this->acoes->getFind('assistStatus'),
                'bonusOrder' => $this->acoes->getFind('bonusOrder'),
                'salanova' => $this->acoes->getFind('salanova')
            ]);
        } else {
            redirect("/login");
        }
    }
    public function listViewAllUnfinished($data): void
    {
        $itens = $this->acoes->getAllnull('orders');
        if ($itens) {
            foreach ($itens as &$item) {
                $btnVoid = $this->acoes->counts('orderCart','id_order', $item->id) > 0 ? '<button class="btn btn-danger float-right mb-3" data-bs-toggle="modal" data-bs-target="#alertModalDelete"><i class="fa fa-times"></i></button>' : '<button onclick="deletar(' . $item->id . ')" class="btn btn-danger float-right mb-3"><i class="fa fa-times"></i></button>';
                if ($this->sessao->getRoles() == 0 ) {
                    $its[] = [
                        'id' => $item->id,
                        'order_number' => $item->order_number,
                        'bonus_order' => $item->bonus_order,
                        'order_date' => date('d/m/Y', strtotime($item->order_date)),
                        'created_at' => date('d/m/Y', strtotime($item->created_at)),
                        'hora' => date('H:i', strtotime($item->created_at)),
                        'id_customer' => $this->acoes->getData('customers', $item->id_customer, 'full_name'),
                        'category' => $this->acoes->getData('customerCategory', $this->acoes->getData('customers', $item->id_customer, 'id_category_customer'), 'name'),
                        'id_bonus_order' => $item->bonus_order == 1 ? $this->acoes->getData('bonusOrder', $item->id_bonus_order, 'discount') . '%' : ((floatval($item->discount) + floatval($item->additional_discount)) + floatval($item->cash_payment)) . '%',
                        'status' => $this->acoes->getData('assistStatus', $item->status, 'name') . "<hr style='background: " . $this->acoes->getData('assistStatus', $item->status, 'color') . "; height: 5px; width: 100%;opacity: 1;' />",
                        'id_salesman' => $this->acoes->getData('salesman',  $this->acoes->getData('customers', $item->id_customer, 'id_salesman'), 'name'),
                        'value_total' => $item->bonus_order == 1 ? '--' : number_format($item->value_total, 2, ',', '.'),
                        'value' => $item->bonus_order == 1 ? '--' : number_format($item->value, 2, ',', '.'),
                        'actions' => '<div style="width:110px;"><a class="btn btn-secondary" href="' . ROOT . '/order-to-order/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '">Finish</a>'.$btnVoid.'</div>',
                    ];
                }else {
                if($this->acoes->getData('salesman',  $this->acoes->getData('customers', $item->id_customer, 'id_salesman'), 'id') == $this->acoes->getData('users', $this->acoes->getByField('salesman', 'id_user', $this->sessao->getUser())->id, 'id')){
                $its[] = [
                    'id' => $item->id,
                    'order_number' => $item->order_number,
                    'bonus_order' => $item->bonus_order,
                    'order_date' => date('d/m/Y', strtotime($item->order_date)),
                    'created_at' => date('d/m/Y', strtotime($item->created_at)),
                    'hora' => date('H:i', strtotime($item->created_at)),
                    'id_customer' => $this->acoes->getData('customers', $item->id_customer, 'full_name'),
                    'category' => $this->acoes->getData('customerCategory', $this->acoes->getData('customers', $item->id_customer, 'id_category_customer'), 'name'),
                    'id_bonus_order' => $item->bonus_order == 1 ? $this->acoes->getData('bonusOrder', $item->id_bonus_order, 'discount') . '%' : ((floatval($item->discount) + floatval($item->additional_discount)) + floatval($item->cash_payment)) . '%',
                    'status' => $this->acoes->getData('assistStatus', $item->status, 'name') . "<hr style='background: " . $this->acoes->getData('assistStatus', $item->status, 'color') . "; height: 5px; width: 100%;opacity: 1;' />",
                    'id_salesman' => $this->acoes->getData('salesman',  $this->acoes->getData('customers', $item->id_customer, 'id_salesman'), 'name'),
                    'value_total' => $item->bonus_order == 1 ? '--' : number_format($item->value_total, 2, ',', '.'),
                    'value' => $item->bonus_order == 1 ? '--' : number_format($item->value, 2, ',', '.'),
                    'actions' => '<div style="width: 110px;"><a class="btn btn-secondary" href="' . ROOT . '/order-to-order/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '">Finish</a>'.$btnVoid.'</div>',
                ];
            }
        }
            }
        } else {
            $its = 0;
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }
    public function listViewUnfinished($data): void
    {
        //dd($this->acoes->getFind('salanova'));
        //$count = $this->acoes->countAdd('orders');
        //$page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        //$pager = new \CoffeeCode\Paginator\Paginator();
        //$pager->pager((int)$count, 20, (int)$page[1]);
        //$itens = $this->acoes->paginationAdd('orders', $pager->limit(), $pager->offset(), 'id DESC');
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/orderForm/mainUnfinished", [
                "title" => "Unfinished Order",
                "description" => "List of all orders in the checkout app",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                //'users' => $this->acoes->getFind('users'),
            ]);
        } else {
            redirect("/login");
        }
    }

    public function getClientInfo($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['id']);
        $itens = $this->acoes->getByField('users', 'id', $client->id_customer);
        $relationshipCustomerAddress = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);
        //dd($relationshipCustomerAddress);
        $address = $this->acoes->getByFieldAll('address', 'id', $relationshipCustomerAddress->id_address);
        $category = $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer);
        $salesman = $this->acoes->getByField('users', 'id', $client->id_salesman);

        foreach ($address as &$item) {
            $its[] = [
                'id' => $item->id,
                'type' => $item->type,
                'address_1' => $item->address_1,
                'address_2' => $item->address_2,
                'zipcode' => $item->zipcode,
                'city' => $item->city,
                'id_state' => $item->id_state,
                'id_country' => $item->id_country,
            ];
        }

        header('Content-Type: application/json');
        $json = json_encode(
            [
                'id' => $itens->id,
                'name' => $itens->name,
                'email' => $itens->email,
                'phone' => $itens->phone,

                'phone' => $itens->phone,
                'phone' => $itens->phone,
                'phone' => $itens->phone,

                'salesman_name' => $salesman->name,
                'salesman_email' => $salesman->email,
                'salesman_phone' => $salesman->phone,

                'category_name' => $category->name,
                'category_basic_discount' => $category->basic_discount,
                'category_cash_payment_discount' => $category->cash_payment_discount,
                'category_goal_discount' => $category->goal_discount,
                'category_goal_introduction' => $category->goal_introduction,
                'category_code' => $category->code,
                'address' => $its,
                'id_customer_id' => $client->id,
                'id_salesman' => $client->id_salesman,
                'id_category_customer' => $client->id_category_customer,
                'full_name' => $client->full_name,
                'email' => $client->email,
                'telephone' => $client->telephone,
                'fax' => $client->fax,
                'mobile' => $client->mobile,
                'website' => $client->website,
                'relation_number' => $client->relation_number,
                'bio' => $client->bio,
                'id_address' => $client->id_address,
                'id_customer' => $client->id_customer,
            ]
        );
        exit($json);
    }


    public function getProductInfoCart($data): void
    {
        $itens = $this->acoes->getByFieldAll('orderCart', 'id_customer', $data['id']);

        foreach ($itens as &$item) {
            $productsStock = $this->acoes->getByField('productsStock', 'id', $item->id_stock);
            $its[] = [
                'id' => $item->id,
                'id_product' => $this->acoes->getByField('products', 'id', $item->id_product)->name,
                'id_stock' => $this->acoes->getByField('productsPackaging', 'id', $productsStock->id_package)->name,
                'value' => $item->value,
                'quantity' => $item->quantity,
                'early_discount' => $item->early_discount,
                'order_number' => $item->order_number
            ];
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }

    public function getProductInfo($data): void
    {

        $itens = $this->acoes->getByFieldAll('productsStock', 'id_products', $data['id']);
        foreach ($itens as &$item) {

            $its[] = [
                'id' => $item->id,
                'valor' => $item->value,
                'quantity' => $item->quantity,
                'id_package' => $this->acoes->getByField('productsPackaging', 'id', $item->id_package)->name
            ];
        }
        $json = json_encode($its);
        header('Content-Type: application/json');
        exit($json);
    }


    /**
     * INICIO PEDIDO
     */

    public function listViewNew(): void
    {
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/orderForm/newStepsInit", [
                "title" => "New Request",
                "description" => "In this area you can place orders",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'client' => $this->acoes->getFind('customers'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getByFieldAll('products', 'status', 'true'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'orderNumber' => substr(number_format(time() * Rand(), 0, '', ''), 0, 6),
                'customerCategory' => $this->acoes->getFind('customerCategory'),
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
                'stock' => $this->acoes->getFind('productsStock'),
                'bonusOrder' => $this->acoes->getFind('bonusOrder'),
                'salanova' => $this->acoes->getFind('salanova')
            ]);
        } else {
            redirect("/login");
        }
    }

    public function newOrderAction($data): void
    {
        $order = new Orders();
        $order->id_payment_term = $data['id_payment_term'];
        $order->payment_type = $data['payment_type'];
        $order->cash_payment = $data['cash_payment'];
        $order->order_number = $data['orderNumber'];
        $order->id_customer = $data['id_customer'];
        $order->save();

        $json = $order->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' =>  ROOT . '/order-to-order' . '/' . $order->id . '/' . $order->id_customer . '/' . $order->order_number, 'mensagem' => "Order created successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to place the Order"]);
        header('Content-Type: application/json');
        exit($json);
    }

    //RECUPERANDO STEPI 01 
    public function stepOne($data): void
    {

        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/stepOneUpdate", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
            'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
            'counts' => $this->acoes->counts('orderCart', 'id_order', $data['id']),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function stepOneUpdate($data): void
    {
        $item = (new Orders())->findById($data['id_order']);
        $item->id_payment_term = $data['id_payment_term'];
        $item->payment_type = $data['payment_type'];
        $item->cash_payment = (int)$data['payment_type'] == 2 ? 0 : $data['cash_payment'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Updated data"]) : json_encode(['resp' => 0, 'mensagem' => "Could not update"]);
        header('Content-Type: application/json');
        exit($json);
    }

    //RECUPERANDO STEPI 02
    public function stepTwo($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/stepTwoUpdate", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'commercialOrderAllValue' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'commercialOrderAllValueNotDiscount' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'itens' => $this->acoes->getCommercialOrderAll('orderCart', 'id_order', $data['id']),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function stepTwoUpdate($data): void
    {
        $item = (new Orders())->findById($data['id_order']);
        $item->id_payment_term = $data['id_payment_term'];
        $item->payment_type = $data['payment_type'];
        $item->cash_payment = (int)$data['payment_type'] == 2 ? 0 : $data['cash_payment'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Updated data"]) : json_encode(['resp' => 0, 'mensagem' => "Could not update"]);
        header('Content-Type: application/json');
        exit($json);
    }
    //RECUPERANDO STEPI 02
    public function stepTwoProducts($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/inc/products", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'commercialOrderAllValue' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'commercialOrderAllValueNotDiscount' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'itens' => $this->acoes->getCommercialOrderAll('orderCart', 'id_order', $data['id']),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function newCartAction($data): void
    {

        $quantityRecive = $data['quantity'];
        $product = $this->acoes->getByField('productsStock', 'id', $data['id_stock']);
        $productRecive = $product->article_number;
        $quantityProducts = $this->acoes->getProductsStockcounts('productsStock', $product->id_package, $product->id_variety);

        $order = $this->acoes->getByField('orders', 'id', $data['id_order']);
        $id_category_customer =  $this->acoes->getData('customers',  $order->id_customer, 'id_category_customer');
        $products = $this->acoes->getByFieldTreeAll('productsStock', 'id_package', $product->id_package, 'id_variety', $product->id_variety, 'status', 'TRUE');


        if ($quantityProducts > 1) {
            $qtd = $this->acoes->sumFielQuantity('productsStock', $product->id_package, $product->id_variety);
            if ($quantityRecive > $qtd->total) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }

            foreach ($products as $stock) {
                $productRecive = $stock->article_number;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            if ($quantityRecive > $product->quantity) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }
            $weightRecive = $product->id;
            $valor = $product->value;
        }

        //if ($data['quantity'] > $quantity) {
        //   $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
        //} else {

        /**
         * 01
         * 
         */
        //Volume Condition  Primeiro Calculo
        $volume_condition = $data['volume_condition'] ? $data['volume_condition'] : 0;
        $porcentagem = $volume_condition;
        $resultados = $valor * ($porcentagem / 100);
        $resultado_volume_condition = $valor - $resultados;

        /**
         * 02
         * 
         */
        //Desconto Categoria Segundo Calculo
        $category_discount =  $this->acoes->getData('customerCategory',  $id_category_customer, 'basic_discount');
        $porcentagem_category_discount = $category_discount;
        $resultados_category_discount = $resultado_volume_condition * ($porcentagem_category_discount / 100);
        $resultado_category_discount = $resultado_volume_condition - $resultados_category_discount;

        /**
         * 03
         * 
         */
        //Pagamento Dinheiro 
        $cash_payment_unit = $order->payment_type == 1 ? $order->cash_payment : 0;
        $porcentagem_cash_payment_unit = $cash_payment_unit;
        $resultados_cash_payment_unit = $resultado_category_discount * ($porcentagem_cash_payment_unit / 100);
        $resultado_cash_payment_unit = $resultado_category_discount - $resultados_cash_payment_unit;

        //Pegando peso do item no estoque
        $weight = $this->acoes->getData('productsStock', $weightRecive, 'weight');

        //dd($this->number_format_short(str_replace(",", ".", $weight)));
        //Salvando Produto ao carrinho
        //dd($productRecive);

        //Calcular o ICMS
        $address_customer =  $this->acoes->getByFieldTwoAddress('relationshipCustomerAddress', 'id_customer', $order->id_customer, 'delivery_type', 1);
        //dd($address_customer);
        $address = $this->acoes->getByField('address', 'id', $address_customer->id_address);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $valor_fin = $resultado_cash_payment_unit;
        $porcentagem_fin = $assistTaxRates->tax;
        $resultado_fin = $valor_fin * ($porcentagem_fin / 100);
        $total_value_novo = $resultado_fin + $valor_fin;

        $item = new OrderCart();
        $item->order_status = 0;
        $item->article_number = $productRecive;
        $item->id_customer = $data['cliente_id'];
        $item->id_stock = $weightRecive;
        $item->value = $valor_fin;
        $item->category_discount = $category_discount;
        $item->value_not_discount = str_replace(",", ".", $valor);
        $item->id_order = $data['id_order'];
        $item->quantity = $data['quantity'];
        $item->weight = $weight;
        $item->volume_condition = $volume_condition;
        $item->total_discount = $porcentagem;
        $item->price = $valor;
        $item->value_icms = $total_value_novo;
        $item->save();


        if ($quantityProducts > 1) {
            foreach ($products as &$prod) {
                if ($prod->quantity > 0) {
                    $this->coProductsRelationshipStock->newQtdActionRelationshipOld($prod->id, $item->id, $prod->quantity);
                    if ($prod->quantity >= $quantityRecive) {
                        $prod->quantity -= $quantityRecive;
                        break;
                    }
                    $quantityRecive -= $prod->quantity;
                    $prod->quantity = 0;
                }
            }
            $stocks = $products;
            foreach ($stocks as $stock) {
                $this->coProductsStock->updateQtdAction($stock->id, $stock->quantity);
                $productRecive = $stock->id_products;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            $quantityAtual = ($product->quantity - $quantityRecive);
            $this->coProductsStock->updateQtdAction($product->id, $quantityAtual);

            $weightRecive = $product->id;
            $valor = $product->value;
        }

        $json = $item->id > 0 ? json_encode([
            'resp' => 1, 'modal' => 'new',
            'id' => $item->id_order, 'clientid' => $item->id_customer, 'mensagem' => "Product successfully added"
        ]) : json_encode(['resp' => 0, 'mensagem' => "Unable to add the Product to your order"]);
        //}
        header('Content-Type: application/json');
        exit($json);
    }



    //RECUPERANDO STEPI 03
    public function stepTree($data): void
    {

        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/stepTreeUpdate", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'bonnusOrderAllValue' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'bonnusOrderAllValueNotDiscount' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'itens' => $this->acoes->getBonnusOrderAll('orderCart', 'id_order', $data['id']),
            'bonusOrder' => $this->acoes->getFind('bonusOrder'),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    //RECUPERANDO STEPI 04
    public function stepTreeProducts($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);

        echo $this->view->render("pages/orderForm/steps/inc/products-tree", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'bonusOrder' => $this->acoes->getFind('bonusOrder'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'bonnusOrderAllValue' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'bonnusOrderAllValueNotDiscount' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'itens' => $this->acoes->getBonnusOrderAll('orderCart', 'id_order', $data['id']),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function newCartActionTree($data): void
    {

        $quantityRecive = $data['quantity_bonus_order'];
        $product = $this->acoes->getByField('productsStock', 'id', $data['id_stock_bonus_order']);
        $productRecive = $product->article_number;
        $quantityProducts = $this->acoes->getProductsStockcounts('productsStock', $product->id_package, $product->id_variety);

        $order = $this->acoes->getByField('orders', 'id', $data['id_order_bonus_order']);
        $id_category_customer =  $this->acoes->getData('customers',  $order->id_customer, 'id_category_customer');
        $products = $this->acoes->getByFieldTreeAll('productsStock', 'id_package', $product->id_package, 'id_variety', $product->id_variety, 'status', 'TRUE');

        if ($quantityProducts > 1) {
            $qtd = $this->acoes->sumFielQuantity('productsStock', $product->id_package, $product->id_variety);
            if ($quantityRecive > $qtd->total) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }

            foreach ($products as $stock) {
                $productRecive = $stock->article_number;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            if ($quantityRecive > $product->quantity) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }
            $weightRecive = $product->id;
            $valor = $product->value;
        }

        // $quantity = $this->acoes->getData('productsStock', $data['id_stock_bonus_order'], 'quantity');
        // $order = $this->acoes->getByField('orders', 'id', $data['id_order_bonus_order']);
        // $id_category_customer =  $this->acoes->getData('customers',  $order->id_customer, 'id_category_customer');

        // if ($data['quantity_bonus_order'] > $quantity) {
        //     $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
        // } else {

        //Desconto Categoria 
        $category_discount =  $this->acoes->getData('customerCategory',  $id_category_customer, 'basic_discount');

        //Calcular valor do Desconto do Produto
        //$valor = $this->acoes->getData('productsStock', $data['id_stock_bonus_order'], 'value');
        $porcentagem = 100;
        $resultados = $valor * ($porcentagem / 100);
        $resultado = $valor - $resultados;

        //Pegando peso do item no estoque
        $weight = $this->acoes->getData('productsStock', $weightRecive, 'weight');

        //Calcular o ICMS
        $address_customer =  $this->acoes->getByFieldTwoAddress('relationshipCustomerAddress', 'id_customer', $order->id_customer, 'delivery_type', 1);
        $address = $this->acoes->getByField('address', 'id', $address_customer->id_address);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $valor_fin = $resultado;
        $porcentagem_fin = $assistTaxRates->tax;
        $resultado_fin = $valor_fin * ($porcentagem_fin / 100);
        $total_value_novo = $resultado_fin + $valor_fin;

        //Salvando Produto ao carrinho
        $item = new OrderCart();
        $item->order_status = 0;
        $item->article_number = $productRecive;
        $item->id_customer = $data['client_id_bonus_order'];
        $item->id_stock = $weightRecive;
        $item->value = $valor_fin;
        $item->value_not_discount = str_replace(",", ".", $valor);
        $item->category_discount = $category_discount;
        $item->id_order = $data['id_order_bonus_order'];
        $item->quantity = $data['quantity_bonus_order'];
        $item->weight = $weight;
        $item->bonus_order = 100;
        $item->bonus_type = $data['bonus_type_bonus_order'];
        $item->total_discount = $porcentagem;
        $item->price = $valor;
        $item->value_icms = $total_value_novo;
        $item->save();
        //dd($item);

        if ($quantityProducts > 1) {
            foreach ($products as &$prod) {
                if ($prod->quantity > 0) {
                    $this->coProductsRelationshipStock->newQtdActionRelationshipOld($prod->id, $item->id, $prod->quantity);
                    if ($prod->quantity >= $quantityRecive) {
                        $prod->quantity -= $quantityRecive;
                        break;
                    }
                    $quantityRecive -= $prod->quantity;
                    $prod->quantity = 0;
                }
            }
            $stocks = $products;
            foreach ($stocks as $stock) {
                $this->coProductsStock->updateQtdAction($stock->id, $stock->quantity);
                $productRecive = $stock->id_products;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            $quantityAtual = ($product->quantity - $quantityRecive);
            $this->coProductsStock->updateQtdAction($product->id, $quantityAtual);

            $weightRecive = $product->id;
            $valor = $product->value;
        }


        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Product successfully added"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to add the Product to your order"]);
        //}
        header('Content-Type: application/json');
        exit($json);
    }


    //RECUPERANDO STEPI 04
    public function stepFour($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/stepFourUpdate", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'itens' => $this->acoes->getSalanovaOrderAll('orderCart', 'id_order', $data['id']),
            'salanovaOrderAllValue' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'salanovaOrderAllValueNotDiscount' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'salanova' => $this->acoes->getFind('salanova'),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    //RECUPERANDO STEPI 04
    public function stepFourProducts($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/inc/products-four", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'itens' => $this->acoes->getSalanovaOrderAll('orderCart', 'id_order', $data['id']),
            'salanovaOrderAllValue' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'salanovaOrderAllValueNotDiscount' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'salanova' => $this->acoes->getFind('salanova'),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function newCartActionFour($data): void
    {
        $quantityRecive = $data['quantity_sala_nova'];
        $product = $this->acoes->getByField('productsStock', 'id', $data['id_stock_sala_nova']);
        $productRecive = $product->article_number;
        $quantityProducts = $this->acoes->getProductsStockcounts('productsStock', $product->id_package, $product->id_variety);

        $order = $this->acoes->getByField('orders', 'id', $data['id_order_sala_nova']);
        $id_category_customer =  $this->acoes->getData('customers',  $order->id_customer, 'id_category_customer');
        $products = $this->acoes->getByFieldTreeAll('productsStock', 'id_package', $product->id_package, 'id_variety', $product->id_variety, 'status', 'TRUE');

        if ($quantityProducts > 1) {
            $qtd = $this->acoes->sumFielQuantity('productsStock', $product->id_package, $product->id_variety);
            if ($quantityRecive > $qtd->total) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }

            foreach ($products as $stock) {
                $productRecive = $stock->article_number;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            if ($quantityRecive > $product->quantity) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }
            $weightRecive = $product->id;
            $valor = $product->value;
        }

        // $quantity = $this->acoes->getData('productsStock', $data['id_stock_sala_nova'], 'quantity');
        // $order = $this->acoes->getByField('orders', 'id', $data['id_order_sala_nova']);
        // $id_category_customer =  $this->acoes->getData('customers',  $order->id_customer, 'id_category_customer');

        // if ($data['quantity_sala_nova'] > $quantity) {
        //     $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
        // } else {


        //Pega o Valor do Produto
        //$valor = $this->acoes->getData('productsStock', $weightRecive, 'value');

        /**
         * 01
         * 
         */
        //Desconto Salanova  Primeiro Calculo
        $sala_nova = $data['sala_nova_sala_nova'] ? $data['sala_nova_sala_nova'] : 0;
        $porcentagem = $sala_nova;
        $resultados = $valor * ($porcentagem / 100);
        $resultado_sala_nova = $valor - $resultados;

        /**
         * 02
         * 
         */
        //Pagamento Dinheiro 
        $cash_payment_unit = $order->payment_type == 1 ? $order->cash_payment : 0;
        $porcentagem_cash_payment_unit = $cash_payment_unit;
        $resultados_cash_payment_unit = $resultado_sala_nova * ($porcentagem_cash_payment_unit / 100);
        $resultado_cash_payment_unit = $resultado_sala_nova - $resultados_cash_payment_unit;

        //Pegando peso do item no estoque
        $weight = $this->acoes->getData('productsStock', $weightRecive, 'weight');

        //Calcular o ICMS
        $address_customer =  $this->acoes->getByFieldTwoAddress('relationshipCustomerAddress', 'id_customer', $order->id_customer, 'delivery_type', 1);
        $address = $this->acoes->getByField('address', 'id', $address_customer->id_address);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $valor_fin = $resultado_cash_payment_unit;
        $porcentagem_fin = $assistTaxRates->tax;
        $resultado_fin = $valor_fin * ($porcentagem_fin / 100);
        $total_value_novo = $resultado_fin + $valor_fin;

        //Salvando Produto ao carrinho
        $item = new OrderCart();
        $item->order_status = 0;
        $item->article_number = $productRecive;
        $item->id_customer = $data['cliente_id_sala_nova'];
        $item->id_stock = $weightRecive;
        $item->value = $valor_fin;
        $item->value_not_discount = str_replace(",", ".", $valor);
        $item->salanova = $data['sala_nova_sala_nova'];
        $item->id_order = $data['id_order_sala_nova'];
        $item->quantity = $data['quantity_sala_nova'];
        $item->weight = $weight;
        $item->total_discount = $porcentagem;
        $item->price = $valor;
        $item->value_icms = $total_value_novo;
        $item->save();

        if ($quantityProducts > 1) {
            foreach ($products as &$prod) {
                if ($prod->quantity > 0) {
                    $this->coProductsRelationshipStock->newQtdActionRelationshipOld($prod->id, $item->id, $prod->quantity);
                    if ($prod->quantity >= $quantityRecive) {
                        $prod->quantity -= $quantityRecive;
                        break;
                    }
                    $quantityRecive -= $prod->quantity;
                    $prod->quantity = 0;
                }
            }
            $stocks = $products;
            foreach ($stocks as $stock) {
                $this->coProductsStock->updateQtdAction($stock->id, $stock->quantity);
                $productRecive = $stock->id_products;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            $quantityAtual = ($product->quantity - $quantityRecive);
            $this->coProductsStock->updateQtdAction($product->id, $quantityAtual);

            $weightRecive = $product->id;
            $valor = $product->value;
        }

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Product successfully added"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to add the Product to your order"]);
        //}
        header('Content-Type: application/json');
        exit($json);
    }



    //RECUPERANDO STEPI 05
    public function stepSix($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/stepSixUpdate", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'aditionalDiscountOrderAllValue' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'aditionalDiscountOrderAllValueNotDiscount' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'itens' => $this->acoes->getAditionalDiscountOrderAll('orderCart', 'id_order', $data['id']),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function stepSixUpdate($data): void
    {
        $item = (new Orders())->findById($data['id_order']);
        $item->id_payment_term = $data['id_payment_term'];
        $item->payment_type = $data['payment_type'];
        $item->cash_payment = (int)$data['payment_type'] == 2 ? 0 : $data['cash_payment'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Updated data"]) : json_encode(['resp' => 0, 'mensagem' => "Could not update"]);
        header('Content-Type: application/json');
        exit($json);
    }
    //RECUPERANDO STEPI 05
    public function stepSixProducts($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        echo $this->view->render("pages/orderForm/steps/inc/products-six", [
            //'customerPaymentTypeConditions' => $this->acoes->getByFieldTwoAll('customerCreditDeadline', 'id_customer_category', $client->id_category_customer, 'type_payment', $order->payment_type),
            'client' => $client,
            'order' => $order,
            'orderNumber' => $data['pedido'],
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'customerCategory' => $this->acoes->getFind('customerCategory'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'aditionalDiscountOrderAllValue' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
            'aditionalDiscountOrderAllValueNotDiscount' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'stock' => $this->acoes->getFind('productsStock'),
            'itens' => $this->acoes->getAditionalDiscountOrderAll('orderCart', 'id_order', $data['id']),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function newCartActionSix($data): void
    {
        $quantityRecive = $data['quantity_aditional_discount'];
        $product = $this->acoes->getByField('productsStock', 'id', $data['id_stock_aditional_discount']);
        $productRecive = $product->article_number;
        $quantityProducts = $this->acoes->getProductsStockcounts('productsStock', $product->id_package, $product->id_variety);

        $order = $this->acoes->getByField('orders', 'id', $data['id_order_aditional_discount']);
        $id_category_customer =  $this->acoes->getData('customers',  $order->id_customer, 'id_category_customer');

        $products = $this->acoes->getByFieldTreeAll('productsStock', 'id_package', $product->id_package, 'id_variety', $product->id_variety, 'status', 'TRUE');

        if ($quantityProducts > 1) {
            $qtd = $this->acoes->sumFielQuantity('productsStock', $product->id_package, $product->id_variety);
            if ($quantityRecive > $qtd->total) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }

            foreach ($products as $stock) {
                $productRecive = $stock->article_number;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            if ($quantityRecive > $product->quantity) {
                $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
                header('Content-Type: application/json');
                exit($json);
            }
            $weightRecive = $product->id;
            $valor = $product->value;
        }
        // $quantity = $this->acoes->getData('productsStock', $data['id_stock_aditional_discount'], 'quantity');
        // $order = $this->acoes->getByField('orders', 'id', $data['id_order_aditional_discount']);
        // $id_category_customer =  $this->acoes->getData('customers',  $order->id_customer, 'id_category_customer');

        // if ($data['quantity_aditional_discount'] > $quantity) {
        //     $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
        // } else {

        //Pega o Valor do Produto
        //$valor = $this->acoes->getData('productsStock', $data['id_stock_aditional_discount'], 'value');

        /**
         * 01
         * 
         */
        //Volume Condition  Primeiro Calculo
        $volume_condition = $data['volume_condition_aditional_discount'] ? $data['volume_condition_aditional_discount'] : 0;
        $porcentagem = $volume_condition;
        $resultados = $valor * ($porcentagem / 100);
        $resultado_volume_condition = $valor - $resultados;

        /**
         * 02
         * 
         */
        //Desconto Categoria Segundo Calculo
        $category_discount =  $this->acoes->getData('customerCategory',  $id_category_customer, 'basic_discount');
        switch ($data['aditional_discount_aditional_discount']) {
            case 1:
                $new_category_discount = ($category_discount - 15);
                break;
            case 2:
                $new_category_discount = ($category_discount - 10);
                break;
            case 3:
                $new_category_discount = ($category_discount - 5);
                break;
            case 4:
                $new_category_discount = ($category_discount  + 1);
                break;
            case 5:
                $new_category_discount = ($category_discount + 2);
                break;
            case 6:
                $new_category_discount = ($category_discount + 3);
                break;
            case 7:
                $new_category_discount = ($category_discount + 4);
                break;
            case 8:
                $new_category_discount = ($category_discount + 5);
                break;
            default:
                $new_category_discount = ($category_discount + 0);
                break;
        }
        $porcentagem_category_discount = $new_category_discount;
        $resultados_category_discount = $resultado_volume_condition * ($porcentagem_category_discount / 100);
        $resultado_category_discount = $resultado_volume_condition - $resultados_category_discount;

        /**
         * 03
         * 
         */
        //Pagamento Dinheiro 
        $cash_payment_unit = $order->payment_type == 1 ? $order->cash_payment : 0;
        $porcentagem_cash_payment_unit = $cash_payment_unit;
        $resultados_cash_payment_unit = $resultado_category_discount * ($porcentagem_cash_payment_unit / 100);
        $resultado_cash_payment_unit = $resultado_category_discount - $resultados_cash_payment_unit;

        //Pegando peso do item no estoque
        $weight = $this->acoes->getData('productsStock', $weightRecive, 'weight');

        //Calcular o ICMS
        $address_customer =  $this->acoes->getByFieldTwoAddress('relationshipCustomerAddress', 'id_customer', $order->id_customer, 'delivery_type', 1);
        $address = $this->acoes->getByField('address', 'id', $address_customer->id_address);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $valor_fin = $resultado_cash_payment_unit;
        $porcentagem_fin = $assistTaxRates->tax;
        $resultado_fin = $valor_fin * ($porcentagem_fin / 100);
        $total_value_novo = $resultado_fin + $valor_fin;

        //Salvando Produto ao carrinho
        $item = new OrderCart();
        $item->order_status = 0;
        $item->article_number = $productRecive;
        $item->id_customer = $data['cliente_id_aditional_discount'];
        $item->id_stock = $weightRecive;
        $item->value = $valor_fin;
        $item->category_discount = $category_discount;
        $item->value_not_discount = str_replace(",", ".", $valor);
        $item->id_order = $data['id_order_aditional_discount'];
        $item->quantity = $data['quantity_aditional_discount'];
        $item->weight = $weight;
        $item->volume_condition = $volume_condition;
        $item->aditional_discount = $data['aditional_discount_aditional_discount'];
        $item->total_discount = $porcentagem;
        $item->price = $valor;
        $item->value_icms = $total_value_novo;
        $item->save();

        if ($quantityProducts > 1) {
            foreach ($products as &$prod) {
                if ($prod->quantity > 0) {
                    $this->coProductsRelationshipStock->newQtdActionRelationshipOld($prod->id, $item->id, $prod->quantity);
                    if ($prod->quantity >= $quantityRecive) {
                        $prod->quantity -= $quantityRecive;
                        break;
                    }
                    $quantityRecive -= $prod->quantity;
                    $prod->quantity = 0;
                }
            }
            $stocks = $products;
            foreach ($stocks as $stock) {
                $this->coProductsStock->updateQtdAction($stock->id, $stock->quantity);
                $productRecive = $stock->id_products;
                $weightRecive = $stock->id;
                $valor = $stock->value;
            }
        } else {
            $quantityAtual = ($product->quantity - $quantityRecive);
            $this->coProductsStock->updateQtdAction($product->id, $quantityAtual);

            $weightRecive = $product->id;
            $valor = $product->value;
        }

        $json = $item->id > 0 ? json_encode([
            'resp' => 1, 'modal' => 'new',
            'id' => $item->id_order, 'clientid' => $item->id_customer, 'mensagem' => "Product successfully added"
        ]) : json_encode(['resp' => 0, 'mensagem' => "Unable to add the Product to your order"]);
        //}
        header('Content-Type: application/json');
        exit($json);
    }



    public function listViewNewUpdate($data): void
    {

        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/orderForm/newSteps", [
                "title" => "New Request",
                "description" => "In this area you can place orders",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'clients' => $this->acoes->getFind('customers'),
                'client' => $this->acoes->getByField('customers', 'id', $data['clientid']),
                'variety' => $this->acoes->getFind('productsVariety'),
                'order' => $this->acoes->getByField('orders', 'id', $data['id']),
                'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'orderNumber' => $data['pedido'],
                'customerCategory' => $this->acoes->getFind('customerCategory'),
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
                'total' => $this->acoes->sumFiels('orderCart', 'id_order', $data['id']),
                'totalNot' => $this->acoes->sumFielsNot('orderCart', 'id_order', $data['id']),
                'totalNotICMS' => $this->acoes->sumFielsNotICMS('orderCart', 'id_order', $data['id']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_order', $data['id']),
                'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['id']),
                'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['id']),
                'stock' => $this->acoes->getFind('productsStock'),
                'bonusOrder' => $this->acoes->getFind('bonusOrder'),
                'salanova' => $this->acoes->getFind('salanova'),
                'id' => $data['id'],
                'clientid' => $data['clientid'],

            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewNewUpdateValorTotal($data): void
    {

        echo $this->view->render("pages/orderForm/steps/inc/total", [
            'total' => $this->acoes->sumFiels('orderCart', 'id_order', $data['id']),
            'totalNot' => $this->acoes->sumFielsNot('orderCart', 'id_order', $data['id']),
            'totalNotICMS' => $this->acoes->sumFielsNotICMS('orderCart', 'id_order', $data['id']),
            'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_order', $data['id']),
            "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
            'clients' => $this->acoes->getFind('customers'),
            'client' => $this->acoes->getByField('customers', 'id', $data['clientid']),
            'order' => $this->acoes->getByField('orders', 'id', $data['id']),
            'commercialOrderAllcounts' => $this->acoes->getCommercialOrderAllcounts('orderCart', 'id_order', $data['id']),
            'ditionalDiscountOrderAllcounts' => $this->acoes->getAditionalDiscountOrderAllcounts('orderCart', 'id_order', $data['id']),
            'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['id']),
            'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['id']),
            'stock' => $this->acoes->getFind('productsStock'),
            'bonusOrder' => $this->acoes->getFind('bonusOrder'),
            'salanova' => $this->acoes->getFind('salanova'),
            'id' => $data['id'],
            'clientid' => $data['clientid'],
        ]);
    }

    public function listViewNewTwo($data): void
    {
        if ($this->sessao->getUser()) {
            $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
            $customerCategory = $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer);

            echo $this->view->render("pages/orderForm/new", [
                "title" => "New Request",
                "description" => "In this area you can place orders",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'client' => $client,
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'order' => $this->acoes->getByField('orders', 'order_number', $data['pedido']),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'itens' => $this->acoes->getByFieldAllNull('orderCart', 'id_customer', $data['clientid']),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'orderNumber' => substr(number_format(time() * Rand(), 0, '', ''), 0, 6),
                'customerCategory' => $customerCategory,
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
                'stock' => $this->acoes->getFind('productsStock'),
                'discount' => $this->acoes->getFind('discount'),
                'total' => $this->acoes->sumFiels('orderCart', 'id_order', $data['id']),
                'totalNot' => $this->acoes->sumFielsNot('orderCart', 'id_order', $data['id']),
                'totalNotICMS' => $this->acoes->sumFielsNotICMS('orderCart', 'id_order', $data['id']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']),
                'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['id']),
                'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['id']),
                'bonusOrder' => $this->acoes->getFind('bonusOrder'),
                'salanova' => $this->acoes->getFind('salanova')
            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewNewTree($data): void
    {
        if ($this->sessao->getUser()) {
            $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
            $customerCategory = $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer);
            $relationshipCustomerAddress = $this->acoes->getByFieldAllTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);

            //dd($relationshipCustomerAddress);
            $order = $this->acoes->getByField('orders', 'id', $data['pedido']);

            //dd($this->acoes->sumFielsWeight('orderCart', 'id_order', $data['pedido']));

            echo $this->view->render("pages/orderForm/newDelivery", [
                "title" => "New Request",
                "description" => "In this area you can place orders",
                'client' => $client,
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'order' => $order,
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'itens' => $this->acoes->getCommercialOrderAll('orderCart', 'id_order', $data['pedido']),
                'itensBonnus' => $this->acoes->getBonnusOrderAll('orderCart', 'id_order', $data['pedido']),
                'itensSalanova' => $this->acoes->getSalanovaOrderAll('orderCart', 'id_order', $data['pedido']),
                'itensAditionalDiscount' => $this->acoes->getAditionalDiscountOrderAll('orderCart', 'id_order', $data['pedido']),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'orderNumber' => substr(number_format(time() * Rand(), 0, '', ''), 0, 6),
                'customerCategory' => $customerCategory,
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
                'tipoPagamento' => $this->acoes->getFind('customerCreditDeadline'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'stock' => $this->acoes->getFind('productsStock'),
                'total' => $this->acoes->sumFiels('orderCart', 'id_order', $data['pedido']),
                'totalNot' => $this->acoes->sumFielsNot('orderCart', 'id_order', $data['pedido']),
                'totalNotICMS' => $this->acoes->sumFielsNotICMS('orderCart', 'id_order', $data['pedido']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_order', $data['pedido']),
                'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['pedido']),
                'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['pedido']),
                'aditionalDiscount' => $this->acoes->getAditionalDiscountOrderAllcounts('orderCart', 'id_order', $data['pedido']),
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'aditionalDiscountOrderAllValue' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_icms'),
                'aditionalDiscountOrderAllValueNotDiscount' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_not_discount'),
                'commercialOrderAllValueNotIcms' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value'),
                'commercialOrderAllValue' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_icms'),
                'commercialOrderAllValueNotDiscount' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_not_discount'),
                'bonnusOrderAllValue' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_icms'),
                'bonnusOrderAllValueNotDiscount' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_not_discount'),
                'salanovaOrderAllValue' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_icms'),
                'salanovaOrderAllValueNotDiscount' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['pedido'], 'value_not_discount'),
                'relationshipCustomerAddress' => $relationshipCustomerAddress,
                'address' => $this->acoes->getFind('address'),
                'country' => $this->acoes->getFind('addressCountry'),
                'state' => $this->acoes->getFind('addressState'),
                'category' => $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer),
                'salesman' => $this->acoes->getByField('users', 'id', $client->id_salesman),
                'bonusOrder' => $this->acoes->getFind('bonusOrder'),
                'salanova' => $this->acoes->getFind('salanova')
            ]);
        } else {
            redirect("/login");
        }
    }


    public function listViewOrder($data): void
    {

        if ($this->sessao->getUser()) {
            $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
            $customerCategory = $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer);
            $relationshipCustomerAddress = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);
            $order = $this->acoes->getByField('orders', 'id', $data['id']);

            $salesman = $this->acoes->getByField('salesman', 'id', $client->id_salesman);
            if ($salesman->id_user) {
                $salesmanUser = $this->acoes->getByField('users', 'id', $salesman->id_user);
            } else {
                $salesmanUser = "";
            }


            echo $this->view->render("pages/orderForm/order", [
                "title" => "Orders",
                "description" => "In this area you can place orders",
                'client' => $client,
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'order' =>  $order,
                'tax' => $this->acoes->getByField('assistTaxRates', 'id', $order->tax),
                'status' => $this->acoes->getFind('assistStatus'),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'itens' => $this->acoes->getCommercialOrderAll('orderCart', 'id_order', $data['id']),
                'itensBonnus' => $this->acoes->getBonnusOrderAll('orderCart', 'id_order', $data['id']),
                'itensSalanova' => $this->acoes->getSalanovaOrderAll('orderCart', 'id_order', $data['id']),
                'itensAditionalDiscount' => $this->acoes->getAditionalDiscountOrderAll('orderCart', 'id_order', $data['id']),
                'customerCategory' => $customerCategory,
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
                'stock' => $this->acoes->getFind('productsStock'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'tipoPagamento' => $this->acoes->getByField('customerCreditDeadline', 'id', $order->id_payment_term),
                'package' => $this->acoes->getFind('productsPackaging'),
                'total' => $this->acoes->sumFiels('orderCart', 'id_customer', $data['clientid']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']),
                'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['id']),
                'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['id']),
                'relationshipCustomerAddress' => $relationshipCustomerAddress,
                'address' => $this->acoes->getByField('address', 'id', $order->delivery_address),
                'country' => $this->acoes->getFind('addressCountry'),
                'state' => $this->acoes->getFind('addressState'),
                'category' => $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer),
                'salesman' => $salesman,
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'salesmanUser' => $salesmanUser,
                'total' => $this->acoes->sumFiels('orderCart', 'id_order', $data['id']),
                'totalNot' => $this->acoes->sumFielsNot('orderCart', 'id_order', $data['id']),
                'totalNotICMS' => $this->acoes->sumFielsNotICMS('orderCart', 'id_order', $data['id']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_order', $data['id']),
                'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['id']),
                'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['id']),
                'aditionalDiscount' => $this->acoes->getAditionalDiscountOrderAllcounts('orderCart', 'id_order', $data['id']),
                'aditionalDiscountOrderAllValue' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
                'aditionalDiscountOrderAllValueNotDiscount' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
                'commercialOrderAllValue' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
                'commercialOrderAllValueNotDiscount' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
                'bonnusOrderAllValue' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
                'bonnusOrderAllValueNotDiscount' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
                'salanovaOrderAllValue' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value_icms'),
                'salanovaOrderAllValueNotDiscount' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
                'bonusOrder' => $this->acoes->getFind('bonusOrder'),
                'salanova' => $this->acoes->getFind('salanova')
            ]);
        } else {
            redirect("/login");
        }
    }


    public function getListProducts($data): void
    {
        echo $this->view->render("pages/orderForm/inc/products", [
            'itens' => $this->acoes->getByFieldAllNull('orderCart', 'id_customer', $data['id']),
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'stock' => $this->acoes->getFind('productsStock'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'bonusOrder' => $this->acoes->getFind('bonusOrder'),
            'salanova' => $this->acoes->getFind('salanova')
        ]);
    }

    public function getPriceOrder($data): void
    {
        $sum = $this->acoes->sumFiels('orderCart', 'id_customer', $data['id']);

        $json = $this->acoes->getByFieldAllNull('orderCart', 'id_customer', $data['id'], 'order_number') ? json_encode(['total' => $sum->value]) : json_encode(['total' => 0]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function newAction($data): void
    {

        $stock = new ProductsStock();
        $stock->id = $this->code->uuid();
        $stock->quantity = $data['quantity'];
        $stock->id_package = $data['id_package'];
        $stock->save();

        if ($stock->id > 0) {
            $item = new Products();
            $item->name = $data['input_name'];
            $item->id_category = $data['id_category'];
            $item->id_variety = $data['id_variety'];
            $item->id_sales_unit = $data['id_sales_unit'];
            $item->id_chemical_treatment = $data['id_chemical_treatment'];
            $item->id_stock = $stock->id;
            $item->batch = $data['batch'];
            $item->maturity = $data['maturity'];
            $item->save();
        }

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Product registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the Product"]);

        header('Content-Type: application/json');
        exit($json);
    }




    public function newOrderTreeAction($data): void
    {
        //$desconto = $data['additional_discount'] + $data['cash_payment'] + $data['discount_form'];
        $order_date = date('Y-m-d H:i:s', strtotime($data['delivery-date']));

        $order = new Orders();
        $order->id_customer = $data['id_customer'];
        $order->value = $data['totalParc'];
        $order->value_total = $data['total_form'];
        $order->order_number = $data['orderNumber'];
        $order->order_date = $order_date;
        $order->discount = $data['discount_form'];
        $order->status = 1;
        $order->save();

        //dd($order);

        if ($order->id > 0) {
            $datapoll = $this->acoes->getByFieldAll('orderCart', 'id_customer', $data['id_customer']);
            foreach ($datapoll as $dats) {
                //$datapol[] = $dats->id;
                $item = (new OrderCart())->findById($dats->id);
                $item->order_number = $data['orderNumber'];
                $item->save();
            }

            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/orders", 'mensagem' => "Order created successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to place the Order"]);

            header('Content-Type: application/json');
            exit($json);
        }
    }

    public function newOrderFourAction($data): void
    {
        //dd($data);
        $additionalDiscount = $this->acoes->getAditionalDiscountOrderAllcounts('orderCart', 'id_order', $data['id']);
        $order_date = date('Y-m-d H:i:s', strtotime($data['delivery-date']));

        $order = (new Orders())->findById($data['id']);
        $order->value = $data['gross_value'];
        $order->value_total = $data['total_value_db'];
        $order->order_date = $order_date;
        $order->delivery = $data['delivery'];
        $order->freight = $data['freight'];
        $order->tax = $data['tax'];
        $order->delivery_address = $data['delivery_address'];
        $order->id_payment_term = $data['id_payment_term'];
        $order->comments = $data['comment'];

        if ((int)$data['special_client'] == 1) {
            $order->status = 1;
        } else if ((int)$data['quotation'] == 12) {
            $order->status = 12;
        } else {
            $order->status = $additionalDiscount > 0 ? 1 : 2;
        }

        $order->tax_value = $data['tax_value'];
        $order->net_value = $data['net_value'];
        $order->save();


            $json = $order->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/orders", 'mensagem' => "Order created successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to place the Order"]);
            header('Content-Type: application/json');
            exit($json);
        
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('products', 'id', $data['id']);
        //$stock = $this->acoes->getByField('stock', 'id', $itens->id_stock);

        header('Content-Type: application/json');
        $json = json_encode([
            'name' => $itens->name,
            'id' => $itens->id,
            'id_category' => $itens->id_category,
            'id_variety' => $itens->id_variety,
            'id_sales_unit' => $itens->id_sales_unit,
            'id_chemical_treatment' => $itens->id_chemical_treatment,
            'maturity' => $itens->maturity,
            'batch' => $itens->batch,
            'stock_id' => $itens->id,
            'stock_quantity' => $itens->quantity,
            'stock_id_package' => $itens->id_package
        ]);
        exit($json);
    }

    public function updatePaymentType($data): void
    {
        $item = (new Orders())->findById($data['id_order']);
        $item->payment_type = $data['payment_type'];
        $item->cash_payment = $data['cash_payment'];
        $item->id_payment_term = $data['id_payment_term'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => $item->id, 'mensagem' => "OK"]) : json_encode(['resp' => 0, 'mensagem' => "Erro"]);

        header('Content-Type: application/json');
        exit($json);
    }

    public function updateAction($data): void
    {
        $stock = (new ProductsStock())->findById($data['stock_id']);
        $stock->quantity = $data['quantity'];
        $stock->id_package = $data['id_package'];
        $stock->save();

        if ($stock->id > 0) {
            $item = (new Products())->findById($data['id']);
            $item->id_category = $data['id_category'];
            $item->id_variety = $data['id_variety'];
            $item->id_sales_unit = $data['id_sales_unit'];
            $item->id_chemical_treatment = $data['id_chemical_treatment'];
            $item->id_stock = $data['stock_id'];
            $item->batch = $data['batch'];
            $item->maturity = $data['maturity'];
            $item->save();
        }
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Product updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update the Product"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function number_format_short($n, $precision = 1)
    {
        if ($n < 300) {
            $n_format = 0.300;
        } else if ($n < 500) {
            $n_format = 0.500;
        } else if ($n < 1000) {
            $n_format = 1;
        } else if ($n < 900000) {
            $n_format = number_format($n / 1000, $precision);
        } else if ($n < 900000000) {
            $n_format = number_format($n / 1000000, $precision);
        } else if ($n < 900000000000) {
            $n_format = number_format($n / 1000000000, $precision);
        } else {
            $n_format = number_format($n / 1000000000000, $precision);
        }
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }
        return $n_format;
    }
    public function freightCalculationRetirada($data): void
    {
        $address = $this->acoes->getByField('address', 'id', $data['id']);
        $order = $this->acoes->getByField('orders', 'id', $data['idOrder']);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $json = json_encode([
            'id_taxa' => $assistTaxRates->id,
            'taxa' => $assistTaxRates->tax,
            'cash_payment' => $order->cash_payment,
            'mensagem' => "Product updated successfully"
        ]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function freightCalculation($data): void
    {
        $partes = explode(".", $data['weight']);
        //dd($this->number_format_short($partes[0]));
        $address = $this->acoes->getByField('address', 'id', $data['id']);
        $order = $this->acoes->getByField('orders', 'id', $data['idOrder']);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $serviceCode = Correios::SERVICE_SEDEX;
        $zipcodeOrigin = "13825000";
        $zipcodeDestiny = str_replace("-", "", $address->zipcode);
        $weight = $this->number_format_short($partes[0]);
        $format = Correios::FORMAT_ENVELOPE;
        $length = 15;
        $height = 1;
        $width = 10;
        $diameter = 0;
        $ownHand = false;
        $declaredValue = 0;
        $acknowledgmentReceipt = false;

        $corr = $this->correios->calculateShipping($serviceCode, $zipcodeOrigin, $zipcodeDestiny, $weight, $format, $length, $height, $width, $diameter, $ownHand, $declaredValue, $acknowledgmentReceipt);


        $codigo = json_decode(json_encode($corr->Codigo), TRUE);
        $valor = json_decode(json_encode($corr->Valor), TRUE);
        $prazoEntrega = json_decode(json_encode($corr->PrazoEntrega), TRUE);
        $valorSemAdicionais = json_decode(json_encode($corr->ValorSemAdicionais), TRUE);
        $valorMaoPropria = json_decode(json_encode($corr->ValorMaoPropria), TRUE);
        $valorAvisoRecebimento = json_decode(json_encode($corr->ValorAvisoRecebimento), TRUE);
        $valorValorDeclarado = json_decode(json_encode($corr->ValorValorDeclarado), TRUE);
        $entregaDomiciliar = json_decode(json_encode($corr->EntregaDomiciliar), TRUE);
        $entregaSabado = json_decode(json_encode($corr->EntregaSabado), TRUE);
        $erro = json_decode(json_encode($corr->Erro), TRUE);

        $json = json_encode([
            'id_taxa' => $assistTaxRates->id,
            'taxa' => $assistTaxRates->tax,
            'codigo' => $codigo[0],
            'valor' => $valor[0],
            'prazoEntrega' => $prazoEntrega[0],
            'valorSemAdicionais' => $valorSemAdicionais[0],
            'valorMaoPropria' => $valorMaoPropria[0],
            'valorAvisoRecebimento' => $valorAvisoRecebimento[0],
            'valorValorDeclarado' => $valorValorDeclarado[0],
            'entregaDomiciliar' => $entregaDomiciliar[0],
            'entregaSabado' => $entregaSabado[0],
            'cash_payment' => $order->cash_payment,
            'erro' => $erro[0],
            'mensagem' => "Product updated successfully"
        ]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $orderCart = $this->acoes->getByField('orderCart', 'id', $data['id']);
        $rStockCartOrder = $this->acoes->getByFieldAll('relationshipStockCartOrder', 'id_cart_order', $data['id']);
        if ($rStockCartOrder) {
            foreach ($rStockCartOrder as $rsco) {
                $this->coProductsStock->updateQtdAction($rsco->id_stock, $rsco->old_quantity);
                $itemRSCO = (new RelationshipStockCartOrder())->findById($rsco->id);
                $itemRSCO->destroy();
            }
        } else {
            $quantity = $this->acoes->getData('productsStock', $orderCart->id_stock, 'quantity');

            $quantityAtual = ($orderCart->quantity + $quantity);
            $this->coProductsStock->updateQtdAction($orderCart->id_stock, $quantityAtual);
        }

        if ($orderCart->id > 0) {
            $item = (new OrderCart())->findById($orderCart->id);
            $item->destroy();

            $json = $item->id > 0 ? "Item deleted successfully" : "Could not delete selected item";
            exit($json);
        }
    }

    public function deleteUnfinishedAction($data)
    {
        $item = (new Orders())->findById($data['id']);

        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/unfinished-orders", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }

    //Lida com a lógica de retorno de produtos para o estoque
    public function returnProducts()
    {
        //Trás todos os pedidos não finalizados e não cancelados com mais de 24 horas
        $unfinishedOrders = (new Orders())->find("status != 'finalized' AND status != 'canceled' AND TIMESTAMPDIFF(HOUR, created_at, NOW()) > 24")->fetch(true);
        console.log($unfinishedOrders);
        foreach ($unfinishedOrders as $order) {
            $relationshipStockCartOrder = (new RelationshipStockCartOrder())->find("id_cart_order = :id_cart_order", "id_cart_order={$order->id}")->fetch(true);

            foreach ($relationshipStockCartOrder as $relationship) {
                $productStock = (new ProductsStock())->findById($relationship->id_stock);
                $productStock->quantity += $relationship->old_quantity;
                $productStock->save();
            }
            
            // Atualiza o status do pedido para 'returned_to_stock'
            $order->status = 'returned_to_stock';
            $order->save();
        }

        // Retorna uma resposta indicando que a operação foi concluída
        echo json_encode(['message' => 'Products returned to stock successfully']);
    }


}