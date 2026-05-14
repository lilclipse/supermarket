<?php
session_start();

require_once __DIR__ . "/src/Configs/Config.php";
require_once __DIR__ . "/src/Views/BaseTemplate.php";
require_once __DIR__ . "/src/Router/Router.php";

require_once __DIR__ . "/src/Controllers/HomeController.php";
require_once __DIR__ . "/src/Controllers/ProductController.php";
require_once __DIR__ . "/src/Controllers/BasketController.php";
require_once __DIR__ . "/src/Controllers/OrderController.php";
require_once __DIR__ . "/src/Controllers/UserController.php";
require_once __DIR__ . "/src/Controllers/AdminController.php";

use App\Router\Router;

$page = $_GET['page'] ?? '';
$router = new Router();

echo $router->route($page);
