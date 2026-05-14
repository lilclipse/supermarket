<?php

namespace App\Controllers;

use App\Views\BaseTemplate;

class HomeController
{
    public function get(): string
    {
        $content = "
            <div class='card'>
                <h2>Добро пожаловать!</h2>
                <p>Веб-приложение предназначено для автоматизации работы супермаркета.</p>
                <p>Система позволяет просматривать товары, добавлять их в заказ и оформлять покупки.</p>
                <a class='btn' href='/supermarket/products'>Перейти в каталог</a>
            </div>
        ";

        return BaseTemplate::render("Главная", $content);
    }
}