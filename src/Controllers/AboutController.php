<?php

namespace App\Controllers;

use App\Views\BaseTemplate;

class AboutController
{
    public function get(): string
    {
        $content = "
            <div class='card'>
                <h2>О проекте</h2>
                <p>Проект разработан на тему: «Проектирование, разработка и внедрение веб-приложения для ИС “Супермаркет”».</p>
                <p>Цель проекта — создать удобную информационную систему для просмотра товаров и оформления заказов.</p>
            </div>
        ";

        return BaseTemplate::render("О нас", $content);
    }
}