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
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$title}</title>

            <link rel='stylesheet' href='/supermarket/assets/css/style.css'>
        </head>

        <body>

            <header>
                <h1>ИС «Супермаркет»</h1>

                <nav>
                    <a href='/supermarket/'>Главная</a>
                    <a href='/supermarket/products'>Каталог</a>
                    <a href='/supermarket/about'>О нас</a>
                </nav>
            </header>

            <main>
                {$content}
            </main>

            <footer>
                © 2026 ИС «Супермаркет»
            </footer>

        </body>
        </html>
        ";
    }
}