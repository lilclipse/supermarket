<?php

namespace App\Controllers;

use App\Configs\Config;
use App\Services\ProductRepository;
use App\Views\BaseTemplate;
use Throwable;

class OrderController
{
    public function get(): string
    {
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $content = "
                <section class='empty-state'>
                    <div class='empty-icon'>📦</div>
                    <h1>Нечего оформлять</h1>
                    <p>Сначала добавьте товары в корзину.</p>
                    <a class='btn btn-primary' href='index.php?page=products'>Перейти в каталог</a>
                </section>
            ";

            return BaseTemplate::render('Оформление заказа', $content);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleOrder();
        }

        $content = "
            <section class='section-heading'>
                <p>Оформление</p>
                <h1>Данные покупателя</h1>
            </section>

            <form class='form-card' method='post' action='index.php?page=order'>
                <label>
                    ФИО
                    <input type='text' name='customer_name' placeholder='Иванов Иван Иванович' required>
                </label>

                <label>
                    Телефон
                    <input type='text' name='phone' placeholder='+7 900 000-00-00' required>
                </label>

                <label>
                    Email
                    <input type='email' name='email' placeholder='mail@example.com' required>
                </label>

                <label>
                    Адрес доставки
                    <textarea name='address' placeholder='Город, улица, дом, квартира' required></textarea>
                </label>

                <button class='btn btn-primary' type='submit'>Подтвердить заказ</button>
            </form>
        ";

        return BaseTemplate::render('Оформление заказа', $content);
    }

    private function handleOrder(): string
    {
        $customerName = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $cart = $_SESSION['cart'] ?? [];

        if ($customerName === '' || $phone === '' || $email === '' || $address === '') {
            $_SESSION['flash'] = 'Заполните все поля формы.';
            header('Location: index.php?page=order');
            exit;
        }

        $items = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = ProductRepository::find((int)$id);
            if ($product === null) {
                continue;
            }

            $price = (float)$product['price'];
            $sum = $price * (int)$quantity;
            $total += $sum;

            $items[] = [
                'product' => $product,
                'quantity' => (int)$quantity,
                'price' => $price,
                'sum' => $sum,
            ];
        }

        $orderId = null;
        $mode = 'Демо-режим: заказ не сохранён в базу, потому что база данных ещё не настроена.';

        try {
            $pdo = Config::getPDO();
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('INSERT INTO orders (customer_name, phone, email, address, total_sum, status) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$customerName, $phone, $email, $address, $total, 'Новый']);
            $orderId = (int)$pdo->lastInsertId();

            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price, sum_price) VALUES (?, ?, ?, ?, ?)');

            foreach ($items as $item) {
                $itemStmt->execute([
                    $orderId,
                    (int)$item['product']['id'],
                    $item['quantity'],
                    $item['price'],
                    $item['sum'],
                ]);
            }

            $pdo->commit();
            $mode = 'Заказ сохранён в базу данных MySQL.';
        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
        }

        unset($_SESSION['cart']);

        $totalText = number_format($total, 0, ',', ' ');
        $orderText = $orderId ? "№ {$orderId}" : 'принят';
        $safeMode = htmlspecialchars($mode, ENT_QUOTES, 'UTF-8');

        $content = "
            <section class='success-state'>
                <div class='empty-icon'>✅</div>
                <h1>Заказ {$orderText}</h1>
                <p>Спасибо, {$customerName}. Итоговая сумма: <strong>{$totalText} ₽</strong>.</p>
                <p class='hint'>{$safeMode}</p>
                <a class='btn btn-primary' href='index.php?page=products'>Вернуться в каталог</a>
            </section>
        ";

        return BaseTemplate::render('Заказ оформлен', $content);
    }
}
