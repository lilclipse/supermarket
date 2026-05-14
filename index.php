<?php

require_once("./vendor/autoload.php");

use App\Router\Router;

session_start();

$router = new Router();
$url = $_SERVER['REQUEST_URI'];

echo $router->route($url);