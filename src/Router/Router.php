<?php
namespace App\Router;

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;
use App\Controllers\UserController;
use App\Controllers\AdminController;

class Router
{
    public function route(string $page): string
    {
        switch ($page) {
            case 'products':
                return (new ProductController())->index();

            case 'basket':
                return (new BasketController())->index();

            case 'basket_add':
                return (new BasketController())->add();

            case 'basket_remove':
                return (new BasketController())->remove();

            case 'basket_clear':
                return (new BasketController())->clear();

            case 'checkout':
                return (new OrderController())->checkout();

            case 'my_orders':
                return (new OrderController())->myOrders();

            case 'login':
                return (new UserController())->login();

            case 'register':
                return (new UserController())->register();

            case 'logout':
                return (new UserController())->logout();

            case 'admin':
                return (new AdminController())->dashboard();

            case 'admin_products':
                return (new AdminController())->products();

            case 'admin_product_add':
                return (new AdminController())->productAdd();

            case 'admin_product_edit':
                return (new AdminController())->productEdit();

            case 'admin_product_delete':
                return (new AdminController())->productDelete();

            case 'admin_orders':
                return (new AdminController())->orders();

            case 'admin_order_status':
                return (new AdminController())->orderStatus();

            case 'about':
                return (new HomeController())->about();

            default:
                return (new HomeController())->index();
        }
    }
}
