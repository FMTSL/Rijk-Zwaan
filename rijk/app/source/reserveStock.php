<?php
require_once "app/source/Router.php";

$productsStock = new AppProductsStock();

$itens = $productsStock->acoes->getByFieldAll('productsStock', 'status', 'true');

$current_time = time();

foreach ($itens as $item) {
    if ($item->reserved_at === null) {
        $item->reserved_at = date('Y-m-d H:i:s', $current_time); // Reservar o item
    } else {
        $reserved_at = strtotime($item->reserved_at);
        $diff_hours = ($current_time - $reserved_at) / 3600; // Diferença em horas

        if ($diff_hours >= 24) {
            $item->reserved_at = null; // Liberar o item do estoque
        }
    }
    $item->save(); // Salvar as alterações 
}
