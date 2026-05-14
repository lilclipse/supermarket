<?php

namespace App\Router;

use App\Controllers\AboutController;
use App\Controllers\BasketController;
use App\Controllers\HomeController;
use App\Controllers\OrderController;
use App\Controllers\ProductController;

class Router
{
    public function route(string $page): string
    {
        switch ($page) {
            case 'products':
                return (new ProductController())->get();

            case 'basket_add':
                return (new BasketController())->add();

            case 'basket_remove':
                return (new BasketController())->remove();

            case 'basket_clear':
                return (new BasketController())->clear();

            case 'basket':
                return (new BasketController())->get();

            case 'order':
                return (new OrderController())->get();

            case 'about':
                return (new AboutController())->get();

            default:
                return (new HomeController())->get();
        }
    }
}
