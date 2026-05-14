<?php

namespace App\Views;

class BaseTemplate
{
    public static function render(string $title, string $content): string
    {
        return "
        <!DOCTYPE html>
        <html lang='ru'>
        <head>
            <meta charset='UTF-8'>
            <title>{$title}</title>
        </head>
        <body>
            <header>
                <h1>ИС «Супермаркет»</h1>
                <nav>
                    <a href='/supermarket/'>Главная</a> |
                    <a href='/supermarket/products'>Каталог</a> |
                    <a href='/supermarket/about'>О нас</a>
                </nav>
                <hr>
            </header>

            <main>
                {$content}
            </main>
        </body>
        </html>
        ";
    }
}