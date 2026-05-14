<?php
namespace App\Controllers;

use App\Views\BaseTemplate;

class HomeController
{
    public function index(): string
    {
        $content = "
            <section class='hero'>
                <p class='eyebrow'>Информационная система магазина</p>
                <h1>Овощной Движ. Быстро. Удобно. Онлайн.</h1>
                <p>Покупатель оформляет заказ, администратор управляет товарами и статусами заказов.</p>
                <div class='hero-actions'>
                    <a class='btn btn-light' href='/supermarket/index.php?page=products'>Перейти в каталог</a>
                    <a class='btn btn-ghost' href='/supermarket/index.php?page=login'>Войти в систему</a>
                </div>
            </section>

            <section class='features'>
                <div class='feature-card'>
                    <span>🛒</span>
                    <h3>Каталог и корзина</h3>
                    <p>Товары выводятся из базы данных, пользователь добавляет продукты в корзину.</p>
                </div>
                <div class='feature-card'>
                    <span>👤</span>
                    <h3>Авторизация</h3>
                    <p>Есть роли покупателя и администратора, доступ разделяется по функционалу.</p>
                </div>
                <div class='feature-card'>
                    <span>📦</span>
                    <h3>Управление заказами</h3>
                    <p>Администратор просматривает заказы и меняет их статус.</p>
                </div>
            </section>
        ";

        return BaseTemplate::render('Главная', $content);
    }

    public function about(): string
    {
        $content = "
            <section class='panel'>
                <h1>О проекте</h1>
                <p>Тема: «Проектирование, разработка и внедрение веб-приложения для ИС “Овощной Движ”».</p>
                <p>Система предназначена для автоматизации работы продуктового магазина: просмотра товаров, оформления заказов и администрирования каталога.</p>
                <div class='role-grid'>
                    <div class='role-card'>
                        <h3>Покупатель</h3>
                        <ul>
                            <li>Регистрация и вход</li>
                            <li>Просмотр каталога</li>
                            <li>Корзина и оформление заказа</li>
                            <li>Просмотр своих заказов</li>
                        </ul>
                    </div>
                    <div class='role-card'>
                        <h3>Администратор</h3>
                        <ul>
                            <li>Просмотр всех заказов</li>
                            <li>Изменение статусов заказов</li>
                            <li>Добавление товаров</li>
                            <li>Редактирование и удаление товаров</li>
                        </ul>
                    </div>
                </div>
            </section>
        ";

        return BaseTemplate::render('О проекте', $content);
    }
}
