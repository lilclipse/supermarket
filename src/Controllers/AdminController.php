<?php
namespace App\Controllers;

use App\Configs\Config;
use App\Views\BaseTemplate;

class AdminController
{
    private function checkAdmin(): void
    {
        if (($_SESSION['role'] ?? '') !== 'admin') {
            $_SESSION['flash'] = 'Доступ только для администратора.';
            Config::redirect('login');
        }
    }

    public function dashboard(): string
    {
        $this->checkAdmin();

        $pdo = Config::getPDO();
        $productsCount = $pdo->query("SELECT COUNT(*) FROM products WHERE is_deleted = 0")->fetchColumn();
        $ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

        $content = "
            <section class='panel'>
                <h1>Админ-панель</h1>
                <p>Управление товарами и заказами супермаркета.</p>

                <div class='admin-grid'>
                    <a class='admin-card' href='/supermarket/index.php?page=admin_products'>
                        <span>🛍️</span>
                        <h3>Товары</h3>
                        <p>Всего товаров: {$productsCount}</p>
                    </a>
                    <a class='admin-card' href='/supermarket/index.php?page=admin_orders'>
                        <span>📦</span>
                        <h3>Заказы</h3>
                        <p>Всего заказов: {$ordersCount}</p>
                    </a>
                </div>
            </section>
        ";

        return BaseTemplate::render('Админ-панель', $content);
    }

    public function products(): string
    {
        $this->checkAdmin();

        $pdo = Config::getPDO();
        $products = $pdo->query("SELECT * FROM products WHERE is_deleted = 0 ORDER BY id DESC")->fetchAll();

        $content = "<section class='panel'><div class='panel-title'><h1>Управление товарами</h1><a class='btn' href='/supermarket/index.php?page=admin_product_add'>Добавить товар</a></div>";
        $content .= "<div class='table-wrap'><table><tr><th>ID</th><th>Название</th><th>Категория</th><th>Цена</th><th>Остаток</th><th>Действия</th></tr>";

        foreach ($products as $product) {
            $id = (int)$product['id'];
            $name = BaseTemplate::escape($product['name']);
            $category = BaseTemplate::escape($product['category']);
            $price = number_format((float)$product['price'], 0, '.', ' ');

            $content .= "
                <tr>
                    <td>{$id}</td>
                    <td>{$name}</td>
                    <td>{$category}</td>
                    <td>{$price} ₽</td>
                    <td>{$product['stock']}</td>
                    <td>
                        <a href='/supermarket/index.php?page=admin_product_edit&id={$id}'>Изменить</a>
                        |
                        <a class='link-danger' href='/supermarket/index.php?page=admin_product_delete&id={$id}'>Удалить</a>
                    </td>
                </tr>
            ";
        }

        $content .= "</table></div></section>";

        return BaseTemplate::render('Товары', $content);
    }

    public function productAdd(): string
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo = Config::getPDO();
            $stmt = $pdo->prepare("
                INSERT INTO products (name, category, description, price, stock)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                strip_tags($_POST['name']),
                strip_tags($_POST['category']),
                strip_tags($_POST['description']),
                (float)$_POST['price'],
                (int)$_POST['stock']
            ]);

            $_SESSION['flash'] = 'Товар добавлен.';
            Config::redirect('admin_products');
        }

        return BaseTemplate::render('Добавить товар', $this->productForm());
    }

    public function productEdit(): string
    {
        $this->checkAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $pdo = Config::getPDO();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $pdo->prepare("
                UPDATE products
                SET name = ?, category = ?, description = ?, price = ?, stock = ?
                WHERE id = ?
            ");

            $stmt->execute([
                strip_tags($_POST['name']),
                strip_tags($_POST['category']),
                strip_tags($_POST['description']),
                (float)$_POST['price'],
                (int)$_POST['stock'],
                $id
            ]);

            $_SESSION['flash'] = 'Товар обновлён.';
            Config::redirect('admin_products');
        }

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            $_SESSION['flash'] = 'Товар не найден.';
            Config::redirect('admin_products');
        }

        return BaseTemplate::render('Изменить товар', $this->productForm($product));
    }

    public function productDelete(): string
    {
        $this->checkAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $pdo = Config::getPDO();

        $stmt = $pdo->prepare("UPDATE products SET is_deleted = 1 WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['flash'] = 'Товар удалён в архив.';
        Config::redirect('admin_products');
    }

    private function productForm(array $product = []): string
    {
        $name = BaseTemplate::escape($product['name'] ?? '');
        $category = BaseTemplate::escape($product['category'] ?? '');
        $description = BaseTemplate::escape($product['description'] ?? '');
        $price = BaseTemplate::escape((string)($product['price'] ?? ''));
        $stock = BaseTemplate::escape((string)($product['stock'] ?? '0'));

        return "
            <section class='panel narrow'>
                <h1>" . (isset($product['id']) ? 'Изменить товар' : 'Добавить товар') . "</h1>
                <form method='post' class='form'>
                    <label>Название</label>
                    <input name='name' required value='{$name}'>

                    <label>Категория</label>
                    <input name='category' required value='{$category}' placeholder='Фрукты, Овощи, Напитки'>

                    <label>Описание</label>
                    <textarea name='description' required>{$description}</textarea>

                    <label>Цена</label>
                    <input name='price' type='number' step='0.01' required value='{$price}'>

                    <label>Остаток</label>
                    <input name='stock' type='number' required value='{$stock}'>

                    <button class='btn' type='submit'>Сохранить</button>
                </form>
            </section>
        ";
    }

    public function orders(): string
    {
        $this->checkAdmin();

        $pdo = Config::getPDO();
        $orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll();

        $content = "<section class='panel'><h1>Все заказы</h1><div class='table-wrap'><table>";
        $content .= "<tr><th>№</th><th>Покупатель</th><th>Телефон</th><th>Сумма</th><th>Статус</th><th>Дата</th><th>Изменить статус</th></tr>";

        foreach ($orders as $order) {
            $sum = number_format((float)$order['total'], 0, '.', ' ');
            $status = $this->statusName($order['status']);
            $id = (int)$order['id'];

            $content .= "
                <tr>
                    <td>{$id}</td>
                    <td>" . BaseTemplate::escape($order['customer_name']) . "</td>
                    <td>" . BaseTemplate::escape($order['phone']) . "</td>
                    <td>{$sum} ₽</td>
                    <td>{$status}</td>
                    <td>{$order['created_at']}</td>
                    <td>
                        <form method='post' action='/supermarket/index.php?page=admin_order_status&id={$id}' class='inline-form'>
                            <select name='status'>
                                {$this->statusOptions($order['status'])}
                            </select>
                            <button class='small-btn'>OK</button>
                        </form>
                    </td>
                </tr>
            ";
        }

        $content .= "</table></div></section>";

        return BaseTemplate::render('Все заказы', $content);
    }

    public function orderStatus(): string
    {
        $this->checkAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $status = $_POST['status'] ?? 'new';

        $allowed = ['new', 'processing', 'delivery', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            $status = 'new';
        }

        $pdo = Config::getPDO();
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        $_SESSION['flash'] = 'Статус заказа обновлён.';
        Config::redirect('admin_orders');
    }

    private function statusName(string $status): string
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

    private function statusOptions(string $current): string
    {
        $statuses = [
            'new' => 'Новый',
            'processing' => 'В обработке',
            'delivery' => 'Доставляется',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменён'
        ];

        $html = '';
        foreach ($statuses as $value => $name) {
            $selected = $value === $current ? 'selected' : '';
            $html .= "<option value='{$value}' {$selected}>{$name}</option>";
        }

        return $html;
    }
}
