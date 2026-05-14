<?php

namespace App\Controllers;

use App\Views\BaseTemplate;

class AboutController
{
    public function get(): string
    {
        $content = "
            <section class='section-heading'>
                <p>О проекте</p>
                <h1>ИС «Супермаркет»</h1>
            </section>

            <section class='panel text-panel'>
                <p>Веб-приложение разработано для автоматизации работы супермаркета. Система позволяет просматривать каталог товаров, добавлять продукты в корзину и оформлять заказ.</p>
                <p>Проект выполнен на PHP с использованием простой MVC-структуры, MySQL/phpMyAdmin и локального сервера XAMPP.</p>

                <div class='tech-list'>
                    <span>PHP</span>
                    <span>MySQL</span>
                    <span>PDO</span>
                    <span>HTML</span>
                    <span>CSS</span>
                    <span>XAMPP</span>
                </div>
            </section>
        ";

        return BaseTemplate::render('О проекте', $content);
    }
}
