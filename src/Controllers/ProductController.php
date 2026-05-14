<?php

namespace App\Controllers;

use App\Views\BaseTemplate;
use App\Configs\Config;

class ProductController
{
    public function get(): string
    {
        $pdo = Config::getPDO();
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();

        $icons = [
            "Молочные продукты" => "🥛",
            "Выпечка" => "🍞",
            "Фрукты" => "🍎",
            "Мясо и птица" => "🍗",
            "Овощи" => "🥔",
            "Напитки" => "🧃",
            "Бакалея" => "🍝"
        ];

        $content = "<section class='catalog'>";
        $content .= "<h2>Каталог продуктов</h2>";
        $content .= "<div class='products'>";

        foreach ($products as $product) {
            $name = htmlspecialchars($product['name']);
            $category = htmlspecialchars($product['category']);
            $description = htmlspecialchars($product['description']);
            $price = number_format($product['price'], 0, '.', ' ');
            $icon = $icons[$product['category']] ?? "🛒";

            $content .= "
                <div class='product-card'>
                    <div class='product-preview'>
                        <span>{$icon}</span>
                    </div>

                    <div class='product-category'>{$category}</div>
                    <h3>{$name}</h3>
                    <p>{$description}</p>

                    <div class='product-bottom'>
                        <strong>{$price} ₽</strong>
                        <a class='btn' href='/supermarket/index.php?page=basket_add&id={$product['id']}'>В корзину</a>
                    </div>
                </div>
            ";
        }

        $content .= "</div>";
        $content .= "</section>";

        return BaseTemplate::render("Каталог", $content);
    }
}