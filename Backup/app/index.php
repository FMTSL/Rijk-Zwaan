<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
//phpinfo();
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/source/Functions.php";
require_once __DIR__ . "/source/Router.php";

use CoffeeCode\DataLayer\Connect;

$conn = Connect::getInstance();
$error = Connect::getError();

if ($error) {
    echo $error->getMessage();
    die();
}
