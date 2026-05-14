<?php

namespace App\Router;

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\AboutController;

class Router
{
    public function route(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", trim($path, "/"));

        $page = $pieces[1] ?? "";

        switch ($page) {
            case "products":
                $controller = new ProductController();
                return $controller->get();

            case "about":
                $controller = new AboutController();
                return $controller->get();

            default:
                $controller = new HomeController();
                return $controller->get();
        }
    }
}