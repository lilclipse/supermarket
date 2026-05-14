<?php

namespace App\Views;

class BaseTemplate
{
    public static function render(string $title, string $content): string
    {
        $cartCount = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $cartCount = array_sum($_SESSION['cart']);
        }

        $flash = '';
        if (isset($_SESSION['flash'])) {
            $message = htmlspecialchars($_SESSION['flash'], ENT_QUOTES, 'UTF-8');
            $flash = "<div class='flash'>{$message}</div>";
            unset($_SESSION['flash']);
        }

        return "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$title}</title>
    <link rel='stylesheet' href='assets/css/style.css'>
</head>
<body>
    <header class='site-header'>
        <a class='logo' href='index.php'>
            <span class='logo-mark'>S</span>
            <span>Supermarket</span>
        </a>

        <nav class='nav'>
            <a href='index.php'>Главная</a>
            <a href='index.php?page=products'>Каталог</a>
            <a href='index.php?page=basket'>Корзина <span class='badge'>{$cartCount}</span></a>
            <a href='index.php?page=about'>О проекте</a>
        </nav>
    </header>

    {$flash}

    <main class='page'>
        {$content}
    </main>

    <footer class='footer'>
        <span>© 2026 ИС «Супермаркет»</span>
        <span>Учебный PHP MVC-проект</span>
    </footer>
</body>
</html>";
    }
}
