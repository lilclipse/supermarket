<?php

namespace App\Controllers;

use App\Services\ProductRepository;
use App\Views\BaseTemplate;

class BasketController
{
    public function get(): string
    {
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $content = "
                <section class='empty-state'>
                    <div class='empty-icon'>🛒</div>
                    <h1>Корзина пуста</h1>
                    <p>Добавьте товары из каталога, чтобы оформить заказ.</p>
                    <a class='btn btn-primary' href='index.php?page=products'>Перейти в каталог</a>
                </section>
            ";

            return BaseTemplate::render('Корзина', $content);
        }

        $total = 0;
        $rows = '';

        foreach ($cart as $id => $quantity) {
            $product = ProductRepository::find((int)$id);
            if ($product === null) {
                continue;
            }

            $name = htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8');
            $price = (float)$product['price'];
            $sum = $price * (int)$quantity;
            $total += $sum;

            $priceText = number_format($price, 0, ',', ' ');
            $sumText = number_format($sum, 0, ',', ' ');

            $rows .= "
                <tr>
                    <td>{$name}</td>
                    <td>{$priceText} ₽</td>
                    <td>{$quantity}</td>
                    <td>{$sumText} ₽</td>
                    <td><a class='link-danger' href='index.php?page=basket_remove&id={$id}'>Удалить</a></td>
                </tr>
            ";
        }

        $totalText = number_format($total, 0, ',', ' ');

        $content = "
            <section class='section-heading'>
                <p>Корзина</p>
                <h1>Ваш заказ</h1>
            </section>

            <section class='panel'>
                <table class='cart-table'>
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Цена</th>
                            <th>Кол-во</th>
                            <th>Сумма</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>{$rows}</tbody>
                </table>

                <div class='cart-summary'>
                    <span>Итого</span>
                    <strong>{$totalText} ₽</strong>
                </div>

                <div class='actions-row'>
                    <a class='btn btn-light' href='index.php?page=products'>Продолжить покупки</a>
                    <a class='btn btn-primary' href='index.php?page=order'>Оформить заказ</a>
                    <a class='btn btn-muted' href='index.php?page=basket_clear'>Очистить корзину</a>
                </div>
            </section>
        ";

        return BaseTemplate::render('Корзина', $content);
    }

    public function add(): string
    {
        $id = (int)($_GET['id'] ?? 0);
        $product = ProductRepository::find($id);

        if ($product === null) {
            $_SESSION['flash'] = 'Товар не найден.';
            $this->redirect('index.php?page=products');
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
        $_SESSION['flash'] = 'Товар добавлен в корзину.';
        $this->redirect('index.php?page=products');
    }

    public function remove(): string
    {
        $id = (int)($_GET['id'] ?? 0);

        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            $_SESSION['flash'] = 'Товар удалён из корзины.';
        }

        $this->redirect('index.php?page=basket');
    }

    public function clear(): string
    {
        unset($_SESSION['cart']);
        $_SESSION['flash'] = 'Корзина очищена.';
        $this->redirect('index.php?page=basket');
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
