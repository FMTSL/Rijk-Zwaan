<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Classe\Sessao;
use Source\Classe\Acoes;
use Source\Models\RelationshipStockCartOrder;

use function JBZoo\Data\json;

class AppRelationshipStockCartOrder
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


  public function newQtdActionRelationshipOld($id_stock, $id_cart_order, $old_quantity)
  {
    $item = new RelationshipStockCartOrder();
    $item->id_stock = $id_stock;
    $item->id_cart_order = $id_cart_order;
    $item->old_quantity = $old_quantity;
    $item->save();
  }


  public function deleteRelationshipAction($id)
  {
    $item = (new RelationshipStockCartOrder())->findById($id);
    $item->destroy();
  }
}