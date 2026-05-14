<!-- Подключение CSS с тёмной темой -->
<link rel="stylesheet" href="/supermarket/assets/css/style_dark_toggle.css">

<header>
    <h1>Supermarket</h1>
    <nav>
        <a href="index.php?page=">Главная</a>
        <a href="index.php?page=products">Каталог</a>
        <a href="index.php?page=basket">Корзина</a>
        <a href="index.php?page=about">О проекте</a>
        <a href="index.php?page=login">Вход</a>
        <a href="index.php?page=register">Регистрация</a>
        <button id="theme-toggle" style="margin-left:20px; padding:6px 12px; border-radius:8px; cursor:pointer;">🌙/☀️</button>
    </nav>
</header>

<main>
    <?= $content ?>
</main>

<!-- Подключение JS для переключения темы -->
<script src="/supermarket/src/Views/theme_toggle.js"></script>