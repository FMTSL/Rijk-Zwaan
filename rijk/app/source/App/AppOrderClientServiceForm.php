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
use Source\Models\RelationshipStockCartOrder;
use Source\Models\Products;

use function JBZoo\Data\json;

class AppOrderClientServiceForm
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

    public function listView(): void
    {
        $count = $this->acoes->countAdd('orders');
        $page = explode("=", htmlspecialchars($_SERVER['REQUEST_URI']));
        $pager = new \CoffeeCode\Paginator\Paginator();
        $pager->pager((int)$count, 20, (int)$page[1]);
        $itens = $this->acoes->paginationAdd('orders', $pager->limit(), $pager->offset(), 'id DESC');
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/orderFormClientService/main", [
                "title" => "Order Client Service",
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

    public function listViewAll($data): void
    {
        $itens = $this->acoes->getFind('orders');
        if ($itens) {
            foreach ($itens as &$item) {
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
                        'net_value' => $item->bonus_order == 1 ? '--' : 'R$ ' . number_format($item->net_value, 2, ',', '.'),
                        'actions' => '<a class="btn btn-slin btn-warning" onclick="gerarPDF(' . $item->id . ',' . $item->id_customer . ',' . $item->order_number . ')" href="#"><i class="fa fa-print"></i></a> 
                        <a class="btn btn-slin btn-info" href="' . ROOT . '/order-to-orders/client-service/logistics/' . $item->id . '/' . $item->id_customer . '/' . $item->order_number . '"><i class="fa fa-eye"></i></a>',
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

            echo $this->view->render("pages/logisticsClientService/order", [
                "title" => "Orders Client Service",
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
                'quotation' => $this->acoes->getQuotationcounts('orderCart', 'id_order', $data['id']),
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
                'aditionalDiscountOrderAllValue' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
                'aditionalDiscountOrderAllValueNotDiscount' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
                'commercialOrderAllValue' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
                'commercialOrderAllValueNotDiscount' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
                'bonnusOrderAllValue' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
                'bonnusOrderAllValueNotDiscount' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
                'salanovaOrderAllValue' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
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

    public function listViewOrderPending($data): void
    {
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

        echo $this->view->render("pages/logisticsClientService/orderPending", [
            "title" => "Orders Client Service",
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
            'quotation' => $this->acoes->getQuotationcounts('orderCart', 'id_order', $data['id']),
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
            'aditionalDiscountOrderAllValue' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
            'aditionalDiscountOrderAllValueNotDiscount' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'commercialOrderAllValue' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
            'commercialOrderAllValueNotDiscount' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'bonnusOrderAllValue' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
            'bonnusOrderAllValueNotDiscount' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'salanovaOrderAllValue' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
            'salanovaOrderAllValueNotDiscount' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            "userRoles" => $this->sessao->getRoles(),
                "userName" => $this->acoes->getData('users', $this->sessao->getUser(), 'name'),
                "userEmail" => $this->acoes->getData('users', $this->sessao->getUser(), 'email'),
            'bonusOrder' => $this->acoes->getFind('bonusOrder'),
            'salanova' => $this->acoes->getFind('salanova')

        ]);
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
        echo $this->view->render("pages/logisticsClientService/orderPrint", [
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
            'aditionalDiscountOrderAllValue' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
            'aditionalDiscountOrderAllValueNotDiscount' => $this->acoes->getAditionalDiscountOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'commercialOrderAllValue' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
            'commercialOrderAllValueNotDiscount' => $this->acoes->getCommercialOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'bonnusOrderAllValue' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
            'bonnusOrderAllValueNotDiscount' => $this->acoes->getBonnusOrderAllsum('orderCart', 'id_order', $data['id'], 'value_not_discount'),
            'salanovaOrderAllValue' => $this->acoes->getSalanovaOrderAllsum('orderCart', 'id_order', $data['id'], 'value'),
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
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/orders", 'mensagem' => "Product updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update the Product"]);
        header('Content-Type: application/json');
        exit($json);
    }
}