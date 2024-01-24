<?php
function dd($params = [], $die = true)
{
    echo '<pre>';
    print_r($params);
    echo '</pre>';

    if ($die) die();
}

/**
 * Redireciona o usuario para a URL informada e finaliza a operação
 * 
 * @param string $url URL a ser redirecionada
 * @return void
 */
function redirect(string $url){
    header('Location: ' . $url);
    exit;
}