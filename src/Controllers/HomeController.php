<?php

namespace App\Controllers;

use App\Views\BaseTemplate;

class HomeController
{
    public function get(): string
    {
        $content = "
        <section class='hero'>
            <div class='hero-kicker'>Информационная система</div>
            <h1>Супермаркет. Быстро. Удобно. Онлайн.</h1>
            <p>Просматривайте продукты, добавляйте их в корзину и оформляйте заказ за пару кликов.</p>
            <div class='hero-actions'>
                <a class='btn btn-primary' href='index.php?page=products'>Перейти в каталог</a>
                <a class='btn btn-light' href='index.php?page=basket'>Открыть корзину</a>
            </div>
        </section>

        <section class='cards'>
            <article class='info-card'>
                <span class='icon'>🥬</span>
                <h3>Свежие продукты</h3>
                <p>Каталог с основными товарами супермаркета: молочная продукция, овощи, фрукты, выпечка и напитки.</p>
            </article>
            <article class='info-card'>
                <span class='icon'>🛒</span>
                <h3>Корзина</h3>
                <p>Покупатель может добавлять товары, менять состав заказа и видеть итоговую стоимость.</p>
            </article>
            <article class='info-card'>
                <span class='icon'>📦</span>
                <h3>Оформление заказа</h3>
                <p>Заказ сохраняется в базу данных MySQL и может быть обработан сотрудником магазина.</p>
            </article>
        </section>
        ";

        return BaseTemplate::render('Главная', $content);
    }
}
