<?php

namespace App\Controllers;

use App\Views\BaseTemplate;

class ProductController
{
    public function get(): string
    {
        $content = "
            <h2>Каталог товаров</h2>

            <div class='products'>
                <div class='card'>
                    <h3>Молоко</h3>
                    <p>Молоко 3.2%, 1 литр</p>
                    <p><b>Цена:</b> 89 ₽</p>
                    <a class='btn' href='#'>Добавить в корзину</a>
                </div>

                <div class='card'>
                    <h3>Хлеб</h3>
                    <p>Хлеб пшеничный свежий</p>
                    <p><b>Цена:</b> 45 ₽</p>
                    <a class='btn' href='#'>Добавить в корзину</a>
                </div>

                <div class='card'>
                    <h3>Яблоки</h3>
                    <p>Яблоки красные, 1 кг</p>
                    <p><b>Цена:</b> 120 ₽</p>
                    <a class='btn' href='#'>Добавить в корзину</a>
                </div>

                <div class='card'>
                    <h3>Сыр</h3>
                    <p>Сыр Российский, 200 г</p>
                    <p><b>Цена:</b> 180 ₽</p>
                    <a class='btn' href='#'>Добавить в корзину</a>
                </div>
            </div>
        ";

        return BaseTemplate::render("Каталог товаров", $content);
    }
}