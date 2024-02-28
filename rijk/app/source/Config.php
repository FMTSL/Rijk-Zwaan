<?php
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
define("SITE", "RIJK");

$live = true;
if ($live) {
    define("ROOT", "https://homolog.rijkzwaanbrasil.com.br");
} else {
    define("ROOT", "http://localhost");
}
define("URL_BASE", "/var/www/html");

define("DATA_LAYER_CONFIG", [
    "driver" => "pgsql",
    "host" => "rijk.postgres",
    "port" => "5432",
    "dbname" => "aut_rijk",
    "username" => "postgres",
    "passwd" => "02W@9889forev",
    "options" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

/**
 * @param string|null $uri
 * @return string
 */
function url(string $uri = null): string
{
    if ($uri) {
        return ROOT . "/{$uri}";
    }

    return ROOT;
}
