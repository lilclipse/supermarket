<?php
namespace App\Controllers;

use App\Configs\Config;
use App\Views\BaseTemplate;

class BasketController
{
    public function index(): string
    {
        $basket = $_SESSION['basket'] ?? [];
        $content = "<section class='panel'><h1>Корзина</h1>";

        if (empty($basket)) {
            $content .= "<p>Корзина пуста. Добавьте товары из каталога.</p><a class='btn' href='/supermarket/index.php?page=products'>Перейти в каталог</a></section>";
            return BaseTemplate::render('Корзина', $content);
        }

        $products = $this->getBasketProducts($basket);
        $total = 0;

        $content .= "<div class='table-wrap'><table><tr><th>Товар</th><th>Цена</th><th>Количество</th><th>Сумма</th><th></th></tr>";

        foreach ($products as $product) {
            $id = (int)$product['id'];
            $qty = (int)$basket[$id];
            $sum = (float)$product['price'] * $qty;
            $total += $sum;

            $name = BaseTemplate::escape($product['name']);
            $price = number_format((float)$product['price'], 0, '.', ' ');
            $sumText = number_format($sum, 0, '.', ' ');

            $content .= "
                <tr>
                    <td>{$name}</td>
                    <td>{$price} ₽</td>
                    <td>{$qty}</td>
                    <td>{$sumText} ₽</td>
                    <td><a class='link-danger' href='/supermarket/index.php?page=basket_remove&id={$id}'>Удалить</a></td>
                </tr>
            ";
        }

        $totalText = number_format($total, 0, '.', ' ');
        $content .= "</table></div>";
        $content .= "
            <div class='basket-total'>
                <strong>Итого: {$totalText} ₽</strong>
                <div>
                    <a class='btn btn-secondary' href='/supermarket/index.php?page=basket_clear'>Очистить</a>
                    <a class='btn' href='/supermarket/index.php?page=checkout'>Оформить заказ</a>
                </div>
            </div>
        </section>";

        return BaseTemplate::render('Корзина', $content);
    }

    public function add(): string
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            if (!isset($_SESSION['basket'])) {
                $_SESSION['basket'] = [];
            }
            $_SESSION['basket'][$id] = ($_SESSION['basket'][$id] ?? 0) + 1;
            $_SESSION['flash'] = 'Товар добавлен в корзину.';
        }

        Config::redirect('products');
    }

    public function remove(): string
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0 && isset($_SESSION['basket'][$id])) {
            unset($_SESSION['basket'][$id]);
            $_SESSION['flash'] = 'Товар удалён из корзины.';
        }

        Config::redirect('basket');
    }

    public function clear(): string
    {
        unset($_SESSION['basket']);
        $_SESSION['flash'] = 'Корзина очищена.';
        Config::redirect('basket');
    }

    private function getBasketProducts(array $basket): array
    {
        $ids = array_map('intval', array_keys($basket));

        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $pdo = Config::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ({$placeholders})");
        $stmt->execute($ids);

        return $stmt->fetchAll();
    }
}
