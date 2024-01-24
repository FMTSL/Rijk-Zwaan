<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Acoes;
use Source\Classe\Sessao;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\XMLConverter;
use League\Csv\Writer;
use Bcrypt\Bcrypt;
use Source\Models\Users;
use Source\Models\Products;
use Source\Models\ProductsCalibre;
use Source\Models\ProductsPackaging;
use Source\Models\ProductsSalesUnit;
use Source\Models\ProductsStock;
use Source\Models\ProductsVariety;

use function JBZoo\Data\json;

class AppImportsProducts
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
                    'status' => $item->status,
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
            echo $this->view->render("pages/imports/products", [
                "title" => "Import Products",
                "description" => "Import Products",
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
                //print_r($uploaded);
                //print_r($uploaded);
                $stream = fopen($uploaded, "r");
                // print_r($stream);
                $csv = Reader::createFromStream($stream);
                // dd($csv);
                $delimiter = $csv->getDelimiter();
                $delimiter == ";" ? $csv->setDelimiter(";") : $csv->setDelimiter(",");
                $csv->setHeaderOffset(0);
                $stmt = (new Statement());
                $products = $stmt->process($csv);
                foreach ($products as $key => $prods) {
                    $productsFind = $this->acoes->getByField('products', 'batch', $prods['Lote']);
                    $cropFind = $this->acoes->getByField('productsCrop', 'name', $prods['Crop']);
                    $varietyFind = $this->acoes->getByField('productsVariety', 'name', $prods['Variedade']);
                    $chemicalTreatmentFind = $this->acoes->getByField('productsChemicalTreatment', 'name', $prods['Chemical Treatment Name']);

                    if (!$productsFind) {
                        $prod = new Products();
                        $prod->name = $prods['Produto + Unidade de venda'];
                        $prod->id_crop = $cropFind->id;
                        $prod->id_variety = $varietyFind->id;
                        $prod->id_sales_unit = 1;
                        $prod->id_chemical_treatment = $chemicalTreatmentFind ? $chemicalTreatmentFind->id : 1;
                        $prod->batch = $prods['Lote'];
                        $prod->status = true;
                        $prod->save();
                    } else {
                        $prod = (new Products())->findById($productsFind->id);
                        $prod->name = $prods['Produto + Unidade de venda'];
                        $prod->id_crop = $cropFind->id;
                        $prod->id_variety = $varietyFind->id;
                        $prod->id_sales_unit = 1;
                        $prod->id_chemical_treatment = $chemicalTreatmentFind ? $chemicalTreatmentFind->id : 1;
                        $prod->batch = $prods['Lote'];
                        $prod->status = true;
                        $prod->save();
                    }
                }

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
        $itens = $this->acoes->getByField('products', 'id', $data['id']);
        $stock = $this->acoes->getByField('stock', 'id', $itens->id_stock);

        header('Content-Type: application/json');
        $json = json_encode([
            'name' => $itens->name,
            'id' => $itens->id,
            'status' => $itens->status,
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
            $item->status = true;
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