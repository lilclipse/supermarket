<?php
namespace App\Controllers;

use App\Configs\Config;
use App\Views\BaseTemplate;

class OrderController
{
    public function checkout(): string
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Для оформления заказа необходимо войти в аккаунт.';
            Config::redirect('login');
        }

        $basket = $_SESSION['basket'] ?? [];
        if (empty($basket)) {
            $_SESSION['flash'] = 'Корзина пуста.';
            Config::redirect('basket');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->saveOrder();
        }

        $content = "
            <section class='panel narrow'>
                <h1>Оформление заказа</h1>
                <form method='post' class='form'>
                    <label>ФИО</label>
                    <input name='customer_name' required placeholder='Иван Иванов'>

                    <label>Телефон</label>
                    <input name='phone' required placeholder='+7 900 000-00-00'>

                    <label>Email</label>
                    <input name='email' type='email' required placeholder='mail@example.ru'>

                    <label>Адрес доставки</label>
                    <textarea name='address' required placeholder='Город, улица, дом, квартира'></textarea>

                    <button class='btn' type='submit'>Подтвердить заказ</button>
                </form>
            </section>
        ";

        return BaseTemplate::render('Оформление заказа', $content);
    }

    private function saveOrder(): string
    {
        $basket = $_SESSION['basket'] ?? [];
        $pdo = Config::getPDO();

        $ids = array_map('intval', array_keys($basket));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ({$placeholders})");
        $stmt->execute($ids);
        $products = $stmt->fetchAll();

        $total = 0;
        foreach ($products as $product) {
            $total += (float)$product['price'] * (int)$basket[$product['id']];
        }

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, customer_name, phone, email, address, total, status)
            VALUES (?, ?, ?, ?, ?, ?, 'new')
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            strip_tags($_POST['customer_name']),
            strip_tags($_POST['phone']),
            strip_tags($_POST['email']),
            strip_tags($_POST['address']),
            $total
        ]);

        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price, sum_item)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($products as $product) {
            $qty = (int)$basket[$product['id']];
            $price = (float)$product['price'];
            $itemStmt->execute([
                $orderId,
                $product['id'],
                $qty,
                $price,
                $price * $qty
            ]);
        }

        $pdo->commit();

        unset($_SESSION['basket']);
        $_SESSION['flash'] = 'Заказ успешно оформлен.';

        Config::redirect('my_orders');
    }

    public function myOrders(): string
    {
        if (!isset($_SESSION['user_id'])) {
            Config::redirect('login');
        }

        $pdo = Config::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $orders = $stmt->fetchAll();

        $content = "<section class='panel'><h1>Мои заказы</h1>";

        if (empty($orders)) {
            $content .= "<p>У вас пока нет заказов.</p></section>";
            return BaseTemplate::render('Мои заказы', $content);
        }

        $content .= "<div class='table-wrap'><table><tr><th>№</th><th>Сумма</th><th>Статус</th><th>Дата</th></tr>";

        foreach ($orders as $order) {
            $sum = number_format((float)$order['total'], 0, '.', ' ');
            $status = $this->statusName($order['status']);
            $content .= "<tr><td>{$order['id']}</td><td>{$sum} ₽</td><td>{$status}</td><td>{$order['created_at']}</td></tr>";
        }

        $content .= "</table></div></section>";

        return BaseTemplate::render('Мои заказы', $content);
    }

    public function statusName(string $status): string
    {
        $names = [
            'new' => 'Новый',
            'processing' => 'В обработке',
            'delivery' => 'Доставляется',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменён'
        ];

        return $names[$status] ?? $status;
    }
}
