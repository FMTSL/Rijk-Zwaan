<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;
use Source\Models\Products;
use Source\Models\ProductsStock;
use Source\Models\RelationshipCustomerAddress;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\XMLConverter;
use League\Csv\Writer;
use Bcrypt\Bcrypt;
use Source\Models\Users;
use Source\Models\Customers;
use Source\Models\Address;
use Source\Models\Category;
use Source\Models\CustomerCategory;

use function JBZoo\Data\json;

class AppImportsCustomers
{
    private $view;
    private $acoes;
    private $sessao;
    private $bcrypt;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme", "php");
        $this->sessao = new Sessao();
        $this->bcrypt = new Bcrypt();
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
        if ($this->sessao->getUser()) {
            echo $this->view->render("pages/imports/customers", [
                "title" => "Import Customers",
                "description" => "Import Customers",
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

        $upload = new \CoffeeCode\Uploader\File(URL_BASE . "/uploads", "csv");
        $files = $_FILES;
        if (!empty($files["csv"])) {
            $file = $files["csv"];
            try {

                $uploaded = $upload->upload($file, $file["name"]);
                $stream = fopen($uploaded, "r");
                $csv = Reader::createFromStream($stream);
                $delimiter = $csv->getDelimiter();
                $delimiter == ";" ? $csv->setDelimiter(";") : $csv->setDelimiter(",");
                $csv->setHeaderOffset(0);
                $stmt = (new Statement());
                $customers = $stmt->process($csv);
                foreach ($customers as $key => $customer) {
                    $userFind = $this->acoes->getByField('users', 'email', $customer['Email']);
                    if (!$userFind) {
                        $user = new Users();
                        $user->name = $customer['Name'];
                        $user->email = $customer['Email'];
                        $user->phone = str_replace(" ", "", $customer['Telephone']);
                        $user->password = $this->bcrypt->encrypt($customer['Email'], '2a');
                        $user->roles = 3;
                        $user->save();
                    } else {
                        $user = (new Users())->findById($userFind->id);
                        $user->name = $customer['Name'];
                        $user->email = $customer['Email'];
                        $user->phone = str_replace(" ", "", $customer['Telephone']);
                        $user->password = $this->bcrypt->encrypt($customer['Email'], '2a');
                        $user->roles = 3;
                        $user->save();
                    }

                    if ($user->id > 0) {
                        $cliFind = $this->acoes->getByField('customers', 'email', $customer['Email']);
                        $customerCategory = $this->acoes->getByField('customerCategory', 'internal_category_code', $customer['Customer category code']);
                        $category = $this->acoes->getByField('customerCategory', 'name', $customer['Customer category description']);
                        $salesman = $this->acoes->getByField('salesman', 'name', $customer['Debtor salesman abbreviated']);

                        if (!$cliFind) {
                            $cli = new Customers();
                            $cli->full_name = $customer['Full name'];
                            $cli->email = $customer['Email'];
                            $cli->telephone = str_replace(" ", "", $customer['Telephone']);
                            $cli->fax = str_replace(" ", "", $customer['Fax']);
                            $cli->mobile = str_replace(" ", "", $customer['Mobile']);
                            $cli->website = $customer['Internet address'];
                            $cli->relation_number = $customer['Relation number'];
                            $cli->bio = $customer['Bio customer'];
                            $cli->cnpj = $customer['VAT number'];
                            $cli->id_salesman = $salesman ? $salesman->id : null;
                            $cli->id_category = $category ? $category->id : 1;
                            $cli->id_category_customer = $customerCategory ? $customerCategory->id : 1;
                            $cli->id_customer = $user->id;
                            $cli->save();

                            if (!$category) {
                                $cat = new CustomerCategory();
                                $cat->name = $customer['Customer category description'];
                                $cat->slug = str_replace(" ", "-", $customer['Customer category description']);
                                $cat->save();
                            }
                        } else {
                            $cli = (new Customers())->findById($cliFind->id);
                            $cli->full_name = $customer['Full name'];
                            $cli->email = $customer['Email'];
                            $cli->telephone = str_replace(" ", "", $customer['Telephone']);
                            $cli->fax = str_replace(" ", "", $customer['Fax']);
                            $cli->mobile = str_replace(" ", "", $customer['Mobile']);
                            $cli->website = $customer['Internet address'];
                            $cli->relation_number = $customer['Relation number'];
                            $cli->bio = $customer['Bio customer'];
                            $customerCategory = $this->acoes->getByField('customerCategory', 'internal_category_code', $customer['Customer category code']);
                            $cli->id_category_customer = $customerCategory->id;
                            $cli->id_customer = $user->id;
                            $cli->id_category = $category ? $category->id : 1;
                            $cli->save();

                            if (!$category) {
                                $cli->save();
                                $cat = new CustomerCategory();
                                $cat->name = $customer['Customer category description'];
                                $cat->slug = str_replace(" ", "-", $customer['Customer category description']);
                                $cat->save();
                            }
                        }

                        if ($cli->id) {
                            $address = new Address();
                            $address->type = "Master";
                            $address->address_1 = $customer['Address 1'];
                            $address->address_2 = $customer['Address 2'];
                            $address->zipcode = $customer['Zipcode'];
                            $address->city = $customer['City'];
                            $countryCode = $this->acoes->getByField('addressCountry', 'initials', $customer['Country code']);
                            $stateCode = $this->acoes->getByField('addressState', 'uf', $customer['State code']);
                            $address->id_state = $stateCode ? $stateCode->id : 1;
                            $address->id_country = $countryCode ? $countryCode->id : 1;
                            $address->save();

                            if ($cli->id) {
                                $relCustomerAddress = new RelationshipCustomerAddress();
                                $relCustomerAddress->id_address = $address->id;
                                $relCustomerAddress->id_customer = $cli->id;
                                $relCustomerAddress->delivery_type = 1;
                                $relCustomerAddress->save();
                            }
                        }
                    }
                }
                $json = $address->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/customers", 'mensagem' => "Import completed successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to import"]);
                header('Content-Type: application/json');
                exit($json);
            } catch (\Exception $e) {
                echo "<p>(!) {$e->getMessage()}</p>";
            }
        }
    }

    public function updateView($data): void
    {
        $itens = $this->acoes->getByField('products', 'id', $data['id']);
        $stock = $this->acoes->getByField('stock', 'id', $itens->id_stock);

        header('Content-Type: application/json');
        $json = json_encode([
            'name' => $itens->name,
            'id' => $itens->id,
            'id_category' => $itens->id_category,
            'id_variety' => $itens->id_variety,
            'id_calibre' => $itens->id_calibre,
            'id_sales_unit' => $itens->id_sales_unit,
            'id_chemical_treatment' => $itens->id_chemical_treatment,
            'maturity' => $itens->maturity,
            'batch' => $itens->batch,
            'stock_id' => $stock->id,
            'stock_quantity' => $stock->quantity,
            'stock_id_package' => $stock->id_package
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
            $item->name = $data['input_name'];
            $item->id_category = $data['id_category'];
            $item->id_variety = $data['id_variety'];
            $item->id_calibre = $data['id_calibre'];
            $item->id_sales_unit = $data['id_sales_unit'];
            $item->id_chemical_treatment = $data['id_chemical_treatment'];
            $item->id_stock = $data['stock_id'];
            $item->batch = $data['batch'];
            $item->maturity = $data['maturity'];
            $item->save();
        }
        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Item updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item!"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new Products())->findById($data['id']);
        $itemStock = (new ProductsStock())->findById($item->id_stock);

        $item->destroy();
        $itemStock->destroy();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}