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
use Source\Models\ProductsStockClone;
use Source\Models\ProductsVariety;

use function JBZoo\Data\json;

class AppImportsProductsClone
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
                    'id_crop' => $item->id_crop,
                    'id_variety' => $item->id_variety,
                    'batch' => $item->batch,
                    'product_sales_unit' => $item->product_sales_unit,
                    'one_of_sale' => $item->one_of_sale,
                    'packaging_expiration' => $item->packaging_expiration,
                    'treatments' => $item->treatments,
                    'sum_of_qty_in_vwh_local' => $item->sum_of_qty_in_vwh_local,
                    'actions' => '<a class="btn btn-info" href="' . ROOT . '/product/stock/clone/item/' . $item->id . '"><i class="fa fa-box-open"></i></a><button class="btn btn-success" onclick="update(' . $item->id . ')"><i class="fa fa-edit"></i></button>',
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
            echo $this->view->render("pages/imports/productsClone", [
                "title" => "Import Daily Stock",
                "description" => "Import Daily Stock",
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
                //dd($csv);
                //$delimiter = $csv->getDelimiter();
                //$delimiter == ";" ? $csv->setDelimiter(";") : $csv->setDelimiter(",");
                $csv->setDelimiter(";");
                //print_r($delimiter);
                $csv->setHeaderOffset(0);
                $stmt = (new Statement());
                $products = $stmt->process($csv);
                foreach ($products as $key => $prods) {
                    $productsFind = $this->acoes->getByField('productsStockClone', 'batch', $prods['Lote']);
                    if (!$productsFind) {
                        $prod = new ProductsStockClone();
                        $prod->id_crop = $prods['Crop'];
                        $prod->id_variety = $prods['Variedade'];
                        $prod->product_sales_unit = $prods['Produto + Unidade de venda'];
                        $prod->batch = $prods['Lote'];
                        $prod->one_of_sale = $prods['Un de venda'];
                        $prod->packaging_expiration = $prods['Vencimento embalagem'];
                        $prod->treatments = $prods['Tratamentos'];
                        $prod->sum_of_qty_in_vwh_local = $prods['Sum of Qty in VWH Local'];
                        $prod->save();
                    } else {
                        $prod = (new ProductsStockClone())->findById($productsFind->id);
                        $prod->id_crop = $prods['Crop'];
                        $prod->id_variety = $prods['Variedade'];
                        $prod->product_sales_unit = $prods['Produto + Unidade de venda'];
                        $prod->batch = $prods['Lote'];
                        $prod->one_of_sale = $prods['Un de venda'];
                        $prod->packaging_expiration = $prods['Vencimento embalagem'];
                        $prod->treatments = $prods['Tratamentos'];
                        $prod->sum_of_qty_in_vwh_local = $prods['Sum of Qty in VWH Local'];
                        $prod->save();
                    }
                }

                $json = $prod->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/import/products/clone", 'mensagem' => "Import completed successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to import"]);
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
            'id' => $itens->id,
            'id_crop' => $itens->id_crop,
            'batch' => $itens->batch,
            'product_sales_unit' => $itens->product_sales_unit,
            'id_variety' => $itens->id_variety,
            'one_of_sale' => $itens->one_of_sale,
            'packaging_expiration' => $itens->packaging_expiration,
            'treatments' => $itens->treatments,
            'sum_of_qty_in_vwh_local' => $itens->sum_of_qty_in_vwh_local,
        ]);
        exit($json);
    }

    public function updateAction($data): void
    {

        $item = (new ProductsStockClone())->findById($data['id']);
        $item->id_crop = $data['id_crop'];
        $item->batch = $data['batch'];
        $item->product_sales_unit = $data['product_sales_unit'];
        $item->id_variety = $data['id_variety'];
        $item->one_of_sale = $data['one_of_sale'];
        $item->packaging_expiration = $data['packaging_expiration'];
        $item->treatments = $data['treatments'];
        $item->sum_of_qty_in_vwh_local = $data['sum_of_qty_in_vwh_local'];
        $item->save();

        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products/clone", 'mensagem' => "Item updated successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Unable to update item!"]);
        header('Content-Type: application/json');
        exit($json);
    }

    public function deleteAction($data)
    {
        $item = (new ProductsStockClone())->findById($data['id']);
        $item->destroy();


        $json = $item->id > 0 ? json_encode(['resp' => 1, 'modal' => 'new', 'redirect' => ROOT . "/products/clone", 'mensagem' => "Item deleted successfully"]) : json_encode(['resp' => 0, 'mensagem' => "Could not delete selected item"]);
        header('Content-Type: application/json');
        exit($json);
    }
}