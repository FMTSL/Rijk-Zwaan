<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;
use Source\Classe\Correios;
use Source\Models\OrderCart;
use Source\Models\Orders;
use Source\Models\ProductsStock;
use Source\Models\Products;

use function JBZoo\Data\json;

class AppOrderForm
{

    private $view;
    private $acoes;
    private $sessao;
    private $correios;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->correios = new Correios();
        $this->sessao = new Sessao();
        $this->acoes = new Acoes();
    }

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('orders');
        if ($itens) {
            foreach ($itens as &$item) {
                if ($item->order_number != null) {
                    if ($item->status > 0) {
                        $its[] = [
                            'id' => $item->id,
                            'order_number' => $item->order_number,
                            'bonus_order' => $item->bonus_order,
                            'order_date' => date('d/m/Y', strtotime($item->order_date)),
                            'id_customer' => $this->acoes->getData('customers', $item->id_customer, 'full_name'),
                            'id_bonus_order' => $item->bonus_order == 1 ? $this->acoes->getData('bonusOrder', $item->id_bonus_order, 'discount') . '%' : ((floatval($item->discount) + floatval($item->additional_discount)) + floatval($item->cash_payment)) . '%',
                            'status' => $this->acoes->getData('assistStatus', $item->status, 'name'),
                            'id_salesman' => $this->acoes->getData('salesman',  $this->acoes->getData('customers', $item->id_customer, 'id'), 'name'),
                            'value_total' => $item->bonus_order == 1 ? '--' : number_format($item->value_total, 2, ',', '.'),
                            'value' => $item->bonus_order == 1 ? '--' : number_format($item->value, 2, ',', '.'),

                            'actions' => '
                            <a class="btn btn-warning" onclick="gerarPDF(' . $item->id . ',' . $item->id_customer . ',' . $item->order_number . ')" href="#"><i class="fa fa-print"></i></a> <a class="btn btn-info ml-2" href="' . ROOT . '/order-to-orders/logistics/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '"><i class="fa fa-eye"></i></a>',
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
                'products' => $this->acoes->getFind('products'),
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

    public function getClientInfo($data): void
    {
        $client = $this->acoes->getByField('customers', 'id', $data['id']);
        $itens = $this->acoes->getByField('users', 'id', $client->id_customer);
        $relationshipCustomerAddress = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);
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




    public function listViewNew(): void
    {
        if ($this->sessao->getUser()) {
            $salesman = $this->acoes->getByField('salesman', 'id_user', $this->sessao->getUser());
            // $client = $this->sessao->getRoles() == 2 ? $this->acoes->getByFieldAll('customers', 'id_salesman', $salesman->id) : $this->acoes->getFind('customers');

            echo $this->view->render("pages/orderForm/newCli", [
                "title" => "New Request",
                "description" => "In this area you can place orders",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'client' => $this->acoes->getFind('customers'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getFind('products'),
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
                'products' => $this->acoes->getFind('products'),
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
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getFind('products'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'stock' => $this->acoes->getFind('productsStock'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'total' => $this->acoes->sumFiels('orderCart', 'id_customer', $data['clientid']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']),
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
            $relationshipCustomerAddress = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);
            //dd($this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']));
            echo $this->view->render("pages/orderForm/newDelivery", [
                "title" => "New Request",
                "description" => "In this area you can place orders",
                'client' => $client,
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getFind('products'),
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
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'stock' => $this->acoes->getFind('productsStock'),
                'total' => $this->acoes->sumFiels('orderCart', 'id_customer', $data['clientid']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']),
                'relationshipCustomerAddress' => $relationshipCustomerAddress,
                'address' => $this->acoes->getByFieldAll('address', 'id', $relationshipCustomerAddress->id_address),
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


            echo $this->view->render("pages/orderForm/order", [
                "title" => "Orders",
                "description" => "In this area you can place orders",
                'client' => $client,
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getFind('products'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'order' =>  $order,
                'tax' => $this->acoes->getByField('assistTaxRates', 'id', $order->tax),
                'itens' => $this->acoes->getByFieldAll('orderCart', 'id_order', $data['id']),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'orderNumber' => substr(number_format(time() * Rand(), 0, '', ''), 0, 6),
                'customerCategory' => $customerCategory,
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
                'stock' => $this->acoes->getFind('productsStock'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'total' => $this->acoes->sumFiels('orderCart', 'id_customer', $data['clientid']),
                'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']),
                'relationshipCustomerAddress' => $relationshipCustomerAddress,
                'address' => $this->acoes->getByFieldAll('address', 'id', $relationshipCustomerAddress->id_address),
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


    public function getListProducts($data): void
    {
        echo $this->view->render("pages/orderForm/inc/products", [
            'itens' => $this->acoes->getByFieldAllNull('orderCart', 'id_customer', $data['id']),
            'variety' => $this->acoes->getFind('productsVariety'),
            'products' => $this->acoes->getFind('products'),
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

    public function newCartAction($data): void
    {
        $quantity = $this->acoes->getData('productsStock', $data['id_stock'], 'quantity');

        if ($data['quantity'] > $quantity) {
            $json = json_encode(['resp' => 0, 'mensagem' => "The quantity reported exceeds the quantity available in stock."]);
        } else {

            if ($data['discount'] > 0) {
                $valor = $this->acoes->getData('productsStock', $data['id_stock'], 'value');
                $porcentagem = $data['discount'];
                //print_r($valor);
                //print_r($porcentagem);
                $resultados = $valor * ($porcentagem / 100);
                //dd($resultados);

                $resultado = $valor - $resultados;
            } else {
                $resultado = $this->acoes->getData('productsStock', $data['id_stock'], 'value');
                $porcentagem = $data['discount'];
            }


            $weight = $this->acoes->getData('productsStock', $data['id_stock'], 'weight');
            $item = new OrderCart();
            $item->id_product = $data['product'];
            $item->id_customer = $data['cliente_id'];
            $item->id_stock = $data['id_stock'];
            $item->value = $resultado;
            $item->quantity = $data['quantity'];
            $item->weight = $weight;
            $item->early_discount = $porcentagem;
            $item->save();

            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Product successfully added"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to add the Product to your order"]);
        }
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


    public function newOrderAction($data): void
    {
        //$desconto = $data['additional_discount'] + $cash_payment + $data['discount_form'];
        $order_date = date('Y-m-d H:i:s', strtotime($data['delivery-date']));

        $order = new Orders();
        $order->additional_discount = $data['additional_discount'];
        $order->id_payment_term = $data['id_payment_term'];
        $order->payment_type = $data['payment_type'];
        $order->cash_payment = $data['cash_payment'];
        $order->order_number = $data['orderNumber'];
        $order->id_customer = $data['id_customer'];
        $order->discount = $data['discount_form'];
        $order->salanova = $data['salanova'];
        $order->bonus_order = $data['bonus_order'];
        $order->id_bonus_order = $data['id_bonus_order'];
        $order->id_salanova = $data['id_salanova'];
        $order->order_date = $order_date;
        $order->save();

        $json = $order->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' =>  ROOT . '/order-to-order/step-two/' . $order->id_customer . '/' . $order->order_number, 'mensagem' => "Order created successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to place the Order"]);
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
        $desconto = $data['additional_discount'] + $data['cash_payment'] + $data['discount_form'];
        $order_date = date('Y-m-d H:i:s', strtotime($data['delivery-date']));


        $order = (new Orders())->findById($data['id']);
        $order->value = $data['totalParc'];
        $order->value_total = $data['total_form'];
        $order->order_date = $order_date;
        $order->delivery = $data['delivery'];
        $order->freight = $data['freight'];
        $order->tax = $data['tax'];
        $order->delivery_address = $data['delivery_address'];
        $order->id_payment_term = $data['id_payment_term'];
        $order->comments = $data['comment'];

        $order->status = 1;
        $order->save();

        //dd($order);

        if ($order->id > 0) {
            $datapoll = $this->acoes->getByFieldAllNull('orderCart', 'id_customer', $data['id_customer']);
            foreach ($datapoll as $dats) {
                $item = (new OrderCart())->findById($dats->id);
                $item->order_number = $order->order_number;
                $item->save();
            }
            $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/orders", 'mensagem' => "Order created successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to place the Order"]);
            header('Content-Type: application/json');
            exit($json);
        }
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
        if ($n < 90) {
            $n_format = number_format($n / 10, $precision);
            $suffix = '';
        } else if ($n < 999) {
            $n_format = 1;
            $suffix = '';
        } else if ($n < 900000) {
            $n_format = number_format($n / 1000, $precision);
            $suffix = ''; //K
        } else if ($n < 900000000) {
            $n_format = number_format($n / 1000000, $precision);
            $suffix = ''; //M
        } else if ($n < 900000000000) {
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = ''; //B
        } else {
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = ''; //T
        }
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return $n_format;
    }

    public function freightCalculation($data): void
    {

        //dd($this->number_format_short($data['weight']));
        $address = $this->acoes->getByField('address', 'id', $data['id']);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $serviceCode = Correios::SERVICE_SEDEX;
        $zipcodeOrigin = "13825000";
        $zipcodeDestiny = str_replace("-", "", $address->zipcode);
        $weight = $this->number_format_short($data['weight']);
        $format = Correios::FORMAT_CAIXA_PACOTE;
        $length = 15;
        $height = 15;
        $width = 15;
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
            'erro' => $erro[0],
            'mensagem' => "Product updated successfully"
        ]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new OrderCart())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? "Item deleted successfully" : "Could not delete selected item";
        //header('Content-Type: application/json');
        exit($json);
    }
}
