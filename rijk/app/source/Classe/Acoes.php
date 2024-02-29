<?php

namespace Source\Classe;

use Google\Service\Analytics\Column;
use Source\Models\Files;
use Source\Models\Users;
use Source\Models\Orders;
use Source\Models\Discount;
use Source\Models\Address;
use Source\Models\Salesman;
use Source\Models\Products;
use Source\Models\Customers;
use Source\Models\OrderCart;
use Source\Models\BonusOrder;
use Source\Models\Salanova;
use Source\Models\CustomerType;
use Source\Models\ProductsCrop;
use Source\Models\AssistStatus;
use Source\Models\AddressState;
use Source\Models\ProductsStock;
use Source\Models\ProductsStockEuro;
use Source\Models\ProductsStockClone;
use Source\Models\OrderLogistics;
use Source\Models\AssistTaxRates;
use Source\Models\AddressCountry;
use Source\Models\ProductsCalibre;
use Source\Models\ProductsVariety;
use Source\Models\CustomerCategory;
use Source\Models\ProductsSalesUnit;
use Source\Models\ProductsPackaging;
use Source\Models\AssistDeliveryType;
use Source\Models\CustomerPaymentType;
use Source\Models\CustomerCreditDeadline;
use Source\Models\CustomerCategoryToCredit;
use Source\Models\RelationshipCustomerAddress;
use Source\Models\RelationshipStockCartOrder;
use Source\Models\ProductsChemicalTreatment;

class Acoes
{
    private $files;
    private $users;
    private $orders;
    private $address;
    private $discount;
    private $salesman;
    private $products;
    private $customers;
    private $orderCart;
    private $bonusOrder;
    private $salanova;
    private $orderLogistics;
    private $customerType;
    private $productsCrop;
    private $assistStatus;
    private $addressState;
    private $productsStock;
    private $productsStockClone;
    private $addressCountry;
    private $assistTaxRates;
    private $productsVariety;
    private $productsCalibre;
    private $customerCategory;
    private $productsSalesUnit;
    private $productsPackaging;
    private $assistDeliveryType;
    private $customerPaymentType;
    private $customerCreditDeadline;
    private $customerCategoryToCredit;
    private $relationshipCustomerAddress;
    private $relationshipStockCartOrder;
    private $productsChemicalTreatment;

    /**
     *
     * Metodo Construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->files = new Files();
        $this->users = new Users();
        $this->orders = new Orders;
        $this->address = new Address();
        $this->salesman = new Salesman();
        $this->salanova = new Salanova();
        $this->products = new Products();
        $this->discount = new Discount();
        $this->customers = new Customers();
        $this->orderCart = new OrderCart();
        $this->bonusOrder = new BonusOrder();
        $this->customerType = new CustomerType();
        $this->productsCrop = new ProductsCrop();
        $this->addressState = new AddressState();
        $this->assistStatus = new AssistStatus();
        $this->productsStock = new ProductsStock();
        $this->productsStockEuro = new ProductsStockEuro();
        $this->productsStockClone = new ProductsStockClone();
        $this->assistTaxRates = new AssistTaxRates();
        $this->orderLogistics = new OrderLogistics();
        $this->addressCountry = new AddressCountry();
        $this->productsVariety = new ProductsVariety();
        $this->productsCalibre = new ProductsCalibre();
        $this->customerCategory = new CustomerCategory();
        $this->productsSalesUnit = new ProductsSalesUnit();
        $this->productsPackaging = new ProductsPackaging();
        $this->assistDeliveryType = new AssistDeliveryType();
        $this->customerPaymentType = new CustomerPaymentType();
        $this->customerCreditDeadline = new CustomerCreditDeadline();
        $this->customerCategoryToCredit = new CustomerCategoryToCredit();
        $this->productsChemicalTreatment = new ProductsChemicalTreatment();
        $this->relationshipStockCartOrder = new RelationshipStockCartOrder();
        $this->relationshipCustomerAddress = new RelationshipCustomerAddress();
    }

    public function getData($table, $id, $type)
    {
        $item = $this->{$table}->find("id = :id", "id={$id}")->fetch(false);
        return $item->{$type};
    }

    public function getDataTwo($table, $column, $id, $type)
    {
        $item = $this->{$table}->find("{$column} = :{$column}", "{$column}={$id}")->fetch(false);
        return $item->{$type};
    }

    public function getFind(string $table)
    {
        return $this->{$table}->find()->fetch(true);
    }

    public function getByField(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = :{$field}", "{$field}={$valor}")->fetch(false);
    }

    public function getByFieldTwo(string $table, string $field, string $valor, string $field2, string $valor2)
    {
        return $this->{$table}->find("{$field} = :{$field} AND {$field2} = {$valor2}", "{$field}={$valor}")->fetch(false);
    }

    public function getByFieldTwoAddress(string $table, string $field, string $valor, string $field2, string $valor2)
    {
        return $this->{$table}->find("{$field} = :{$field} AND {$field2} = {$valor2} AND id_address is not NULL", "{$field}={$valor}")->fetch(false);
    }

    public function getByFieldTwoAll(string $table, string $field, string $valor, string $field2, string $valor2)
    {
        return $this->{$table}->find("{$field} = :{$field} AND {$field2} = {$valor2}", "{$field}={$valor}")->fetch(true);
    }

    public function getByFieldTree(string $table, string $field, string $valor, string $field2, string $valor2, string $field3, string $valor3)
    {
        return $this->{$table}->find("{$field} = :{$field} AND {$field2} = {$valor2} AND {$field3} = {$valor3}", "{$field}={$valor}")->fetch(false);
    }

    public function getByFieldTreeAll(string $table, string $field, string $valor, string $field2, string $valor2, string $field3, string $valor3)
    {
        return $this->{$table}->find("{$field} = :{$field} AND {$field2} = {$valor2} AND {$field3} = {$valor3}", "{$field}={$valor}")->fetch(true);
    }

    public function getByFieldTreeMenor(string $table, string $field, string $valor, string $field2, string $valor2, string $field3, string $valor3)
    {
        return $this->{$table}->find("{$field} = :{$field} AND {$field2} = {$valor2} AND {$field3} < {$valor3}", "{$field}={$valor}")->order("id DESC")->fetch(false);
    }

    public function getByFieldTreeNull(string $table, string $field, string $valor, string $field2, string $valor2, string $field3, string $valor3)
    {
        return $this->{$table}->find("{$field} = {$valor} AND {$field2} = {$valor2} AND {$field3} is {$valor3}")->fetch(true);
    }

    public function getById(string $table, int $id)
    {
        return $this->{$table}->find($id)->fetch(true);
    }

    public function getByFieldAll(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor}")->fetch(true);
    }

    public function getAllnull(string $table)
    {
        return $this->{$table}->find("status is null")->fetch(true);
    }

    public function getCommercialOrderAll(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND salanova is null AND bonus_order is null AND aditional_discount is null")->fetch(true);
    }

    public function getSalanovaOrderAll(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND category_discount is null AND bonus_order is null AND aditional_discount is null")->fetch(true);
    }

    public function getBonnusOrderAll(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND bonus_order is not null AND salanova is null AND aditional_discount is null")->fetch(true);
    }

    public function getAditionalDiscountOrderAll(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND salanova is null AND bonus_order is null AND aditional_discount is not null")->fetch(true);
    }

    public function getByFieldAllOrder(string $table, string $field, string $valor, string $order)
    {
        return $this->{$table}->find("{$field} = {$valor}")->order("{$order}")->fetch(true);
    }


    public function getByFieldAllLoop(string $table, string $field, string $valor, string $field2, string $valor2)
    {
        return $this->{$table}->find("{$field} = {$valor} AND {$field2} = {$valor2} AND order_number is null")->fetch(true);
    }

    public function getByFieldAllTwo(string $table, string $field, string $valor, string $field2, string $valor2)
    {
        return $this->{$table}->find("{$field} = {$valor} AND {$field2} = {$valor2}")->fetch(true);
    }

    public function getByFieldAllTwoInt(string $table, string $field, int $valor, string $field2, int $valor2)
    {
        return $this->{$table}->find("{$field} = {$valor} AND {$field2} = {$valor2}")->fetch(true);
    }

    public function getByFieldAllTwoNull(string $table, string $field, string $valor, string $field2, string $valor2)
    {
        return $this->{$table}->find("{$field} = {$valor} AND {$field2} = {$valor2} AND order_number is null")->fetch(true);
    }

    public function getByFieldAllNull(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND order_number is null")->fetch(true);
    }

    public function limitOrder(string $table, string $field, string $valor, int $limit, string $field2, string $order)
    {
        return $this->{$table}->find("{$field} ={$valor}")->limit($limit)->order("{$field2} {$order}")->fetch(true);
    }
    public function limitTwoOrder(string $table, string $field, string $valor, string $field3, string $valor3, int $limit, string $field2, string $order)
    {
        return $this->{$table}->find("{$field} ={$valor} AND {$field3} ={$valor3}")->limit($limit)->order("{$field2} {$order}")->fetch(true);
    }

    public function limitOrderFill(string $table, string $field, string $valor, string $field1, string $valor1, string $field3, string $valor3, int $limit, string $field2, string $order)
    {
        return $this->{$table}->find("{$field} = {$valor} AND {$field1} = {$valor1} AND {$field3} < {$valor3} ")->limit($limit)->order("{$field2} {$order}")->fetch(true);
    }

    public function pagination(string $table, string $field, string $valor, int $limit, string $offset, string $order)
    {
        return $this->{$table}->find("{$field} ={$valor}")->limit($limit)->offset($offset)->order($order)->fetch(true);
    }

    public function paginationAdd(string $table, int $limit, string $offset, string $order)
    {
        return $this->{$table}->find()->limit($limit)->offset($offset)->order($order)->fetch(true);
    }

    /**
     * Funções de Soma
     *
     * @param string $table
     * @param string $field
     * @param string $valor
     * @return void
     */
    public function sumFiels(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND order_status = 0", null, "SUM(quantity * value_icms) AS value_icms")->fetch();
    }
    public function sumFielsNotICMS(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND order_status = 0", null, "SUM(quantity * value) AS value")->fetch();
    }
    public function sumFielsNot(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND order_status = 0", null, "SUM(quantity * value_not_discount) AS value_not_discount")->fetch();
    }
    public function sumFielsWeight(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor}", null, "SUM(quantity * weight) AS value")->fetch();
    }

    public function getCommercialOrderAllsum(string $table, string $field, string $valor, string $valorSum)
    {
        return $this->{$table}->find("{$field} = {$valor} AND salanova is null AND bonus_order is null AND aditional_discount is null", null, "SUM(quantity * {$valorSum}) AS {$valorSum}")->fetch();
    }

    public function getSalanovaOrderAllsum(string $table, string $field, string $valor, string $valorSum)
    {
        return $this->{$table}->find("{$field} = {$valor} AND category_discount is null AND bonus_order is null AND aditional_discount is null", null, "SUM(quantity * {$valorSum}) AS {$valorSum}")->fetch();
    }

    public function getBonnusOrderAllsum(string $table, string $field, string $valor, string $valorSum)
    {
        return $this->{$table}->find("{$field} = {$valor} AND bonus_order is not null AND salanova is null AND aditional_discount is null", null, "SUM(quantity * {$valorSum}) AS {$valorSum}")->fetch();
    }

    public function getAditionalDiscountOrderAllsum(string $table, string $field, string $valor, string $valorSum)
    {
        return $this->{$table}->find("{$field} = {$valor} AND salanova is null AND bonus_order is null AND aditional_discount is not null", null, "SUM(quantity * {$valorSum}) AS {$valorSum}")->fetch();
    }

    /**
     * Funções de Contagem
     *
     * @param string $table
     * @param string $field
     * @param string $valor
     * @return void
     */
    public function countAdd(string $table)
    {
        return $this->{$table}->find()->count();
    }

    public function counts(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor}")->count();
    }

    public function getQuotationcounts(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND aditional_discount is not null")->count();
    }

    public function getProductsStockcounts(string $table, string $valor, string $valor2)
    {
        return $this->{$table}->find("id_package = {$valor} AND id_variety = {$valor2} AND status = true")->count();
    }

    public function sumFielQuantity(string $table, string $valor, string $valor2)
    {
        return $this->{$table}->find("id_package = {$valor} AND id_variety = {$valor2} AND status = true", null, "SUM(quantity) AS total")->fetch();
    }


    public function getCommercialOrderAllcounts(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND salanova is null AND bonus_order is null AND aditional_discount is null")->count();
    }

    public function getSalanovaOrderAllcounts(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND category_discount is null AND bonus_order is null AND aditional_discount is null")->count();
    }

    public function getBonnusOrderAllcounts(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND bonus_order is not null AND salanova is null AND aditional_discount is null")->count();
    }

    public function getAditionalDiscountOrderAllcounts(string $table, string $field, string $valor)
    {
        return $this->{$table}->find("{$field} = {$valor} AND salanova is null AND bonus_order is null AND aditional_discount is not null")->count();
    }
}