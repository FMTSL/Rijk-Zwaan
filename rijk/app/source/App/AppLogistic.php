<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;
use Source\Classe\Correios;
use Source\Models\OrderCart;
use Source\Models\OrderLogistics;
use Source\Models\Orders;
use Source\Models\ProductsStock;
use Source\Models\Products;

use function JBZoo\Data\json;
use Dompdf\Dompdf;

class AppLogistic
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
                            'created_at' => date('d/m/Y', strtotime($item->created_at)),
                            'hora' => date('H:i', strtotime($item->created_at)),
                            'id_customer' => $this->acoes->getData('customers', $item->id_customer, 'full_name'),
                            'category' => $this->acoes->getData('customerCategory', $this->acoes->getData('customers', $item->id_customer, 'id_category_customer'), 'name'),
                            'id_bonus_order' => $item->bonus_order == 1 ? $this->acoes->getData('bonusOrder', $item->id_bonus_order, 'discount') . '%' : ((floatval($item->discount) + floatval($item->additional_discount)) + floatval($item->cash_payment)) . '%',
                            'status' => $this->acoes->getData('assistStatus', $item->status, 'name') . "<hr style='background: " . $this->acoes->getData('assistStatus', $item->status, 'color') . "; height: 5px; width: 100%;opacity: 1;' />",
                            'id_salesman' => $this->acoes->getData('salesman',  $this->acoes->getData('customers', $item->id_customer, 'id_salesman'), 'name'),
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
            echo $this->view->render("pages/logistics/main", [
                "title" => "Order logistics",
                "description" => "List of all orders in the app",
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                "itens" => $itens,
                'pager' => $pager->render('p-3 page'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'users' => $this->acoes->getFind('users'),
                'customers' => $this->acoes->getFind('customers'),
                'products' => $this->acoes->getFind('productsStock'),
                'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
                'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
                'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
                'package' => $this->acoes->getFind('productsPackaging'),
                'customerCategory' => $this->acoes->getFind('customerCategory'),
                'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
                'stock' => $this->acoes->getFind('productsStock'),
                'status' => $this->acoes->getFind('assistStatus'),
                'salesman' => $this->acoes->getFind('salesman'),
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
            $client = $this->sessao->getRoles() == 2 ? $this->acoes->getByFieldAll('customers', 'id_salesman', $salesman->id) : $this->acoes->getFind('customers');

            echo $this->view->render("pages/logistics/newCli", [
                "title" => "New Request",
                "description" => "In this area you can place orders",
                'client' => $client,
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'variety' => $this->acoes->getFind('productsVariety'),
                'products' => $this->acoes->getFind('productsStock'),
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


    public function listViewOrder($data): void
    {

        if ($this->sessao->getUser()) {
            $order = $this->acoes->getByField('orders', 'id', $data['id']);
            $client = $this->acoes->getByField('customers', 'id', $order->id_customer);
            $customerCategory = $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer);
            $relationshipCustomerAddress = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);
            if ($relationshipCustomerAddress) {
                if ($order->delivery_address) {
                    $address = $this->acoes->getByField('address', 'id', $order->delivery_address);
                } else {
                    $address = 0;
                }
            } else {
                $address = 0;
            }

            $salesman = $this->acoes->getByField('salesman', 'id', $client->id_salesman);
            if ($salesman->id_user) {
                $salesmanUser = $this->acoes->getByField('users', 'id', $salesman->id_user);
            } else {
                $salesmanUser = "";
            }

            echo $this->view->render("pages/logistics/order", [
                "title" => "Orders Logistic",
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
                'quotation' => $this->acoes->getQuotationcounts('orderCart', 'id_order', $data['id']),
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
                'address' => $address,
                'country' => $this->acoes->getFind('addressCountry'),
                'state' => $this->acoes->getFind('addressState'),
                'category' => $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer),
                'salesman' => $salesman,
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
                "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
                'bonusOrder' => $this->acoes->getFind('bonusOrder'),
                'salanova' => $this->acoes->getFind('salanova')

            ]);
        } else {
            redirect("/login");
        }
    }

    public function listViewOrderPrint($data): void
    {

        // $dompdf = new Dompdf(['is_html5_parser_enabled' => true, "enable_remote" => true]);
        // ob_start();
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $customerCategory = $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer);
        $relationshipCustomerAddress = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        $salesman = $this->acoes->getByField('salesman', 'id', $client->id_salesman);
        //dd($salesman);
        if ($relationshipCustomerAddress) {
            if ($order->delivery_address) {
                $address = $this->acoes->getByField('address', 'id', $order->delivery_address);
            } else {
                $address = 0;
            }
        } else {
            $address = 0;
        }
        echo $this->view->render("pages/logistics/orderPrint", [
            'client' => $client,
            'variety' => $this->acoes->getFind('productsVariety'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'order' =>  $order,
            'tax' => $this->acoes->getByField('assistTaxRates', 'id', $order->tax),
            'status' => $this->acoes->getFind('assistStatus'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'orderNumber' => substr(number_format(time() * Rand(), 0, '', ''), 0, 6),
            'customerCategory' => $customerCategory,
            'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
            'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
            'stock' => $this->acoes->getFind('productsStock'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'countrys' => $this->acoes->getFind('addressCountry'),
            'states' => $this->acoes->getFind('addressState'),
            'relationshipCustomerAddress' => $relationshipCustomerAddress,
            'address' => $address,
            'salesman' => $this->acoes->getByField('users', 'id', $salesman->id_user),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'itens' => $this->acoes->getCommercialOrderAll('orderCart', 'id_order', $data['id']),
            'itensBonnus' => $this->acoes->getBonnusOrderAll('orderCart', 'id_order', $data['id']),
            'itensSalanova' => $this->acoes->getSalanovaOrderAll('orderCart', 'id_order', $data['id']),
            'itensAditionalDiscount' => $this->acoes->getAditionalDiscountOrderAll('orderCart', 'id_order', $data['id']),
            'tipoPagamento' => $this->acoes->getByField('customerCreditDeadline', 'id', $order->id_payment_term),
            'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']),
            'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['id']),
            'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['id']),
            'country' => $this->acoes->getFind('addressCountry'),
            'state' => $this->acoes->getFind('addressState'),
            'category' => $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer),
            'salesmanUser' => $salesman,
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
            "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
            'bonusOrder' => $this->acoes->getFind('bonusOrder'),
            'salanova' => $this->acoes->getFind('salanova')
        ]);

        // $dompdf->loadHtml(ob_get_clean());
        // $dompdf->setPaper('A4');
        // $dompdf->render();
        // $dompdf->stream("file.pdf", ["Attachment" => false]);
    }

    public function listViewOrderPrintPDF($data): void
    {

        $dompdf = new Dompdf(['is_html5_parser_enabled' => true, "enable_remote" => true]);
        ob_start();
        $client = $this->acoes->getByField('customers', 'id', $data['clientid']);
        $customerCategory = $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer);
        $relationshipCustomerAddress = $this->acoes->getByFieldTwo('relationshipCustomerAddress', 'delivery_type', 1, 'id_customer', $client->id);
        $order = $this->acoes->getByField('orders', 'id', $data['id']);
        $salesman = $this->acoes->getByField('salesman', 'id', $client->id_salesman);
        //dd($salesman);
        if ($relationshipCustomerAddress) {
            if ($order->delivery_address) {
                $address = $this->acoes->getByField('address', 'id', $order->delivery_address);
            } else {
                $address = 0;
            }
        } else {
            $address = 0;
        }
        echo $this->view->render("pages/logistics/orderPrintPDF", [
            'client' => $client,
            'variety' => $this->acoes->getFind('productsVariety'),
            'salesUnit' => $this->acoes->getFind('productsSalesUnit'),
            'order' =>  $order,
            'tax' => $this->acoes->getByField('assistTaxRates', 'id', $order->tax),
            'status' => $this->acoes->getFind('assistStatus'),
            'deliveryType' => $this->acoes->getFind('assistDeliveryType'),
            'orderNumber' => substr(number_format(time() * Rand(), 0, '', ''), 0, 6),
            'customerCategory' => $customerCategory,
            'customerPaymentType' => $this->acoes->getFind('customerPaymentType'),
            'customerCreditDeadline' => $this->acoes->getFind('customerCreditDeadline'),
            'stock' => $this->acoes->getFind('productsStock'),
            'productsChemicalTreatment' => $this->acoes->getFind('productsChemicalTreatment'),
            'package' => $this->acoes->getFind('productsPackaging'),
            'countrys' => $this->acoes->getFind('addressCountry'),
            'states' => $this->acoes->getFind('addressState'),
            'relationshipCustomerAddress' => $relationshipCustomerAddress,
            'address' => $address,
            'salesman' => $this->acoes->getByField('users', 'id', $salesman->id_user),
            'products' => $this->acoes->getByFieldAll('productsStock', 'status', 'true'),
            'itens' => $this->acoes->getCommercialOrderAll('orderCart', 'id_order', $data['id']),
            'itensBonnus' => $this->acoes->getBonnusOrderAll('orderCart', 'id_order', $data['id']),
            'itensSalanova' => $this->acoes->getSalanovaOrderAll('orderCart', 'id_order', $data['id']),
            'itensAditionalDiscount' => $this->acoes->getAditionalDiscountOrderAll('orderCart', 'id_order', $data['id']),
            'tipoPagamento' => $this->acoes->getByField('customerCreditDeadline', 'id', $order->id_payment_term),
            'totalWeight' => $this->acoes->sumFielsWeight('orderCart', 'id_customer', $data['clientid']),
            'totalSalanova' => $this->acoes->getSalanovaOrderAllcounts('orderCart', 'id_order', $data['id']),
            'totalBonusOrder' => $this->acoes->getBonnusOrderAllcounts('orderCart', 'id_order', $data['id']),
            'country' => $this->acoes->getFind('addressCountry'),
            'state' => $this->acoes->getFind('addressState'),
            'category' => $this->acoes->getByField('customerCategory', 'id', $client->id_category_customer),
            'salesmanUser' => $salesman,
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
            "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
            'bonusOrder' => $this->acoes->getFind('bonusOrder'),
            'salanova' => $this->acoes->getFind('salanova')
        ]);

        $dompdf->loadHtml(ob_get_clean());
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream("file.pdf", ["Attachment" => false]);
    }


    public function getListProducts($data): void
    {
        echo $this->view->render("pages/logistics/inc/products", [
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
        $item = new OrderCart();
        $item->id_product = $data['product'];
        $item->id_customer = $data['cliente_id'];
        $item->id_stock = $data['id_stock'];
        $item->value = $this->acoes->getByField('productsStock', 'id', $data['id_stock'])->value;
        $item->quantity = $data['quantity'];
        $item->early_discount = !$data['discount_product'] ? 0 : $data['discount_product'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'mensagem' => "Product successfully added"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to add the Product to your order"]);

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

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/orders", 'mensagem' => "Product registered successfully"]) : json_encode(['resp' => 0, 'mensagem' => "It was not possible to register the Product"]);

        header('Content-Type: application/json');
        exit($json);
    }


    public function newOrderAction($data): void
    {
        $cash_payment = $data['cash_payment'] == 0 ? $data['cash_payment'] : 0;
        $desconto = $data['additional_discount'] + $cash_payment + $data['discount_form'];
        $order_date = date('Y-m-d H:i:s', strtotime($data['delivery-date']));

        $order = new Orders();
        $order->additional_discount = $data['additional_discount'];
        $order->id_payment_term = $data['id_payment_term'];
        $order->payment_type = $data['payment_type'];
        $order->cash_payment = $data['cash_payment'];
        $order->order_number = $data['orderNumber'];
        $order->id_customer = $data['id_customer'];
        $order->discount = $desconto;

        $order->order_date = $order_date;
        $order->save();

        $json = $order->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' =>  ROOT . '/order-to-order/step-two/' . $order->id_customer . '/' . $order->order_number, 'mensagem' => "Order created successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to place the Order"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function newOrderTreeAction($data): void
    {
        $desconto = $data['additional_discount'] + $data['cash_payment'] + $data['discount_form'];
        $order_date = date('Y-m-d H:i:s', strtotime($data['delivery-date']));

        $order = new Orders();
        $order->id_customer = $data['id_customer'];
        $order->value = $data['totalParc'];
        $order->value_total = $data['total_form'];
        $order->order_number = $data['orderNumber'];
        $order->order_date = $order_date;
        $order->discount = $desconto;
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
        if ((int)$data['status'] == 11) {
            $orderCart = $this->acoes->getByFieldAll('orderCart', 'id_order', $data['id']);
            foreach ($orderCart as $oc) {
                $stock = $this->acoes->getByField('productsStock', 'id', $oc->id_stock);

                $item = (new ProductsStock())->findById($stock->id);
                $item->order_number = $oc->quantity + $stock->quantity;
                $item->save();
            }
        }

        $item = (new Orders())->findById($data['id']);
        $item->status = $data['status'];
        $item->save();

        $order = $this->acoes->getByField('orderLogistics', 'id_order', $data['id']);

        if ($order) {
            $orderLogistics = (new OrderLogistics())->findById($order->id);
            $orderLogistics->id_status = $data['status'];
            // $orderLogistics->id_order = $data['id_order'];
            // $orderLogistics->tracking_code = $data['tracking_code'];
            // $orderLogistics->information = $data['information'];
            $orderLogistics->save();
        } else {
            $orderLogistics = new OrderLogistics();
            $orderLogistics->id_status = $data['status'];
            $orderLogistics->id_order = $data['id'];
            // $orderLogistics->tracking_code = $data['tracking_code'];
            // $orderLogistics->information = $data['information'];
            $orderLogistics->save();
        }
        // $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/client-service", 'mensagem' => "Product updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update the Product"]);
        // header('Content-Type: application/json');
        // exit($json);
        echo "<script>alert('Product updated successfully'); window.location.href = '" . ROOT . "/orders';</script>";

    }

    public function updateActionQuotation($data): void
    {
        if ((int)$data['status'] == 11) {
            $orderCart = $this->acoes->getByFieldAll('orderCart', 'id_order', $data['id']);
            foreach ($orderCart as $oc) {
                $stock = $this->acoes->getByField('productsStock', 'id', $oc->id_stock);

                $item = (new ProductsStock())->findById($stock->id);
                $item->order_number = $oc->quantity + $stock->quantity;
                $item->save();
            }
        }

        $item = (new Orders())->findById($data['id']);
        $item->status = $data['status'];
        $item->save();

        $order = $this->acoes->getByField('orderLogistics', 'id_order', $data['id']);

        if ($order) {
            $orderLogistics = (new OrderLogistics())->findById($order->id);
            $orderLogistics->id_status = $data['status'];
            // $orderLogistics->id_order = $data['id_order'];
            // $orderLogistics->tracking_code = $data['tracking_code'];
            // $orderLogistics->information = $data['information'];
            $orderLogistics->save();
        } else {
            $orderLogistics = new OrderLogistics();
            $orderLogistics->id_status = $data['status'];
            $orderLogistics->id_order = $data['id'];
            // $orderLogistics->tracking_code = $data['tracking_code'];
            // $orderLogistics->information = $data['information'];
            $orderLogistics->save();
        }
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/orders", 'mensagem' => "Product updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update the Product"]);
        ('Content-Type: application/json');
        exit($json);
    }


    public function freightCalculation($data): void
    {

        //Pegar os produtos + a quantidade e peso de cada um

        //Pegar o Local de Origem e Destino
        // - Rua Campo das Palmas,543 Centro 13825-000 / Holambra-SP


        //Pegar ICMS
        $address = $this->acoes->getByField('address', 'id', $data['id']);
        $assistTaxRates = $this->acoes->getByField('assistTaxRates', 'id_state', $address->id_state);

        $serviceCode = Correios::SERVICE_SEDEX;
        $zipcodeOrigin = "13825000";
        $zipcodeDestiny = str_replace("-", "", $address->zipcode);
        $weight = 1; //$data['weight'];
        $format = Correios::FORMAT_CAIXA_PACOTE;
        $length = 15;
        $height = 15;
        $width = 15;
        $diameter = 0;
        $ownHand = false;
        $declaredValue = 0;
        $acknowledgmentReceipt = false;

        $corr = $this->correios->calculateShipping($serviceCode, $zipcodeOrigin, $zipcodeDestiny, $weight, $format, $length, $height, $width, $diameter, $ownHand, $declaredValue, $acknowledgmentReceipt);



        $json = json_encode([
            'id_taxa' => $assistTaxRates->id,
            'taxa' => $assistTaxRates->tax,
            'codigo' => $corr->Codigo,
            'valor' => $corr->Valor,
            'prazoEntrega' => $corr->PrazoEntrega,
            'valorSemAdicionais' => $corr->ValorSemAdicionais,
            'valorMaoPropria' => $corr->ValorMaoPropria,
            'valorAvisoRecebimento' => $corr->ValorAvisoRecebimento,
            'valorValorDeclarado' => $corr->ValorValorDeclarado,
            'entregaDomiciliar' => $corr->EntregaDomiciliar,
            'entregaSabado' => $corr->EntregaSabado,
            'erro' => $corr->Erro,
            'mensagem' => "Product updated successfully"
        ]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new OrderCart())->findById($data['id']);
        $item->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/orders", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}