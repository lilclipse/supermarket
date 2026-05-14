<?php
namespace App\Views;

class BaseTemplate
{
    public static function render(string $title, string $content): string
    {
        $username = $_SESSION['username'] ?? null;
        $role = $_SESSION['role'] ?? null;
        $basketCount = array_sum($_SESSION['basket'] ?? []);
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $authLinks = '';

        if ($username === null) {
            $authLinks = "
                <a href='/supermarket/index.php?page=login'>Вход</a>
                <a class='nav-pill' href='/supermarket/index.php?page=register'>Регистрация</a>
            ";
        } else {
            if ($role === 'admin') {
                $authLinks .= "<a href='/supermarket/index.php?page=admin'>Админ-панель</a>";
            } else {
                $authLinks .= "<a href='/supermarket/index.php?page=my_orders'>Мои заказы</a>";
            }

            $authLinks .= "
                <span class='user-chip'>{$username}</span>
                <a class='nav-pill nav-pill-dark' href='/supermarket/index.php?page=logout'>Выход</a>
            ";
        }

        $flashHtml = '';
        if ($flash) {
            $flashHtml = "<div class='flash'>{$flash}</div>";
        }

        return "
        <!DOCTYPE html>
        <html lang='ru'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$title}</title>
            <link rel='stylesheet' href='/supermarket/assets/css/style_dark_catalog.css'>
        </head>
        <body>
            <header class='topbar'>
                <a class='brand' href='/supermarket/index.php'>
                    <span class='logo'>О</span>
                    <span>Овощной Движ</span>
                </a>

                <nav>
                    <a href='/supermarket/index.php'>Главная</a>
                    <a href='/supermarket/index.php?page=products'>Каталог</a>
                    <a href='/supermarket/index.php?page=basket'>Корзина <span class='count'>{$basketCount}</span></a>
                    <a href='/supermarket/index.php?page=about'>О проекте</a>
                    {$authLinks}
                </nav>
            </header>

            <main>
                {$flashHtml}
                {$content}
            </main>

            <footer>
                <p>© 2026 ИС «Овощной Движ». Учебный проект.</p>
            </footer>
        </body>
        </html>
        ";
    }

    public static function escape(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}