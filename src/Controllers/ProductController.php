<?php
namespace App\Controllers;

use App\Configs\Config;
use App\Views\BaseTemplate;

class ProductController
{
    public function index(): string
    {
        $pdo = Config::getPDO();
        $stmt = $pdo->query('SELECT * FROM products WHERE is_deleted = 0 ORDER BY id DESC');
        $products = $stmt->fetchAll();

        $icons = [
            'Молочные продукты' => '🥛',
            'Выпечка' => '🍞',
            'Фрукты' => '🍎',
            'Мясо и птица' => '🍗',
            'Овощи' => '🥔',
            'Напитки' => '🧃',
            'Бакалея' => '🍝'
        ];

        $content = "<section class='catalog-head'><h1>Каталог продуктов</h1><p>Выберите товары и добавьте их в корзину.</p></section>";
        $content .= "<section class='products'>";

        foreach ($products as $product) {
            $id = (int)$product['id'];
            $name = BaseTemplate::escape($product['name']);
            $category = BaseTemplate::escape($product['category']);
            $description = BaseTemplate::escape($product['description']);
            $price = number_format((float)$product['price'], 0, '.', ' ');
            $icon = $icons[$product['category']] ?? '🛒';

            $content .= "
                <article class='product-card'>
                    <div class='product-preview'><span>{$icon}</span></div>
                    <span class='tag'>{$category}</span>
                    <h3>{$name}</h3>
                    <p>{$description}</p>
                    <div class='product-bottom'>
                        <strong>{$price} ₽</strong>
                        <a class='btn' href='/supermarket/index.php?page=basket_add&id={$id}'>В корзину</a>
                    </div>
                </article>
            ";
        }

        $content .= "</section>";

        return BaseTemplate::render('Каталог', $content);
    }
}
