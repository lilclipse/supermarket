<?php

require_once __DIR__ . '/src/Configs/Config.php';
require_once __DIR__ . '/src/Services/ProductRepository.php';
require_once __DIR__ . '/src/Views/BaseTemplate.php';
require_once __DIR__ . '/src/Controllers/HomeController.php';
require_once __DIR__ . '/src/Controllers/ProductController.php';
require_once __DIR__ . '/src/Controllers/BasketController.php';
require_once __DIR__ . '/src/Controllers/OrderController.php';
require_once __DIR__ . '/src/Controllers/AboutController.php';
require_once __DIR__ . '/src/Router/Router.php';

use App\Router\Router;

session_start();

$page = $_GET['page'] ?? '';
$router = new Router();

echo $router->route($page);
