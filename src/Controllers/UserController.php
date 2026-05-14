<?php
namespace App\Controllers;

use App\Configs\Config;
use App\Views\BaseTemplate;

class UserController
{
    public function login(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';

            $pdo = Config::getPDO();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$login, $login]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['flash'] = 'Вы успешно вошли.';

                if ($user['role'] === 'admin') {
                    Config::redirect('admin');
                }

                Config::redirect('products');
            }

            $_SESSION['flash'] = 'Неверный логин или пароль.';
            Config::redirect('login');
        }

        $content = "
            <section class='panel narrow'>
                <h1>Вход</h1>
                <form method='post' class='form'>
                    <label>Логин или email</label>
                    <input name='login' required placeholder='admin или user'>

                    <label>Пароль</label>
                    <input name='password' type='password' required placeholder='123456'>

                    <button class='btn' type='submit'>Войти</button>
                </form>
                <p class='hint'>Тестовый админ: <b>admin</b> / <b>123456</b></p>
                <p class='hint'>Тестовый пользователь: <b>user</b> / <b>123456</b></p>
            </section>
        ";

        return BaseTemplate::render('Вход', $content);
    }

    public function register(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm) {
                $_SESSION['flash'] = 'Пароли не совпадают.';
                Config::redirect('register');
            }

            if (mb_strlen($password) < 6) {
                $_SESSION['flash'] = 'Пароль должен быть не короче 6 символов.';
                Config::redirect('register');
            }

            $pdo = Config::getPDO();

            $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check->execute([$username, $email]);

            if ($check->fetch()) {
                $_SESSION['flash'] = 'Пользователь с таким логином или email уже существует.';
                Config::redirect('register');
            }

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([
                strip_tags($username),
                strip_tags($email),
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $_SESSION['flash'] = 'Регистрация завершена. Теперь войдите в аккаунт.';
            Config::redirect('login');
        }

        $content = "
            <section class='panel narrow'>
                <h1>Регистрация</h1>
                <form method='post' class='form'>
                    <label>Логин</label>
                    <input name='username' required>

                    <label>Email</label>
                    <input name='email' type='email' required>

                    <label>Пароль</label>
                    <input name='password' type='password' required>

                    <label>Повторите пароль</label>
                    <input name='confirm_password' type='password' required>

                    <button class='btn' type='submit'>Зарегистрироваться</button>
                </form>
            </section>
        ";

        return BaseTemplate::render('Регистрация', $content);
    }

    public function logout(): string
    {
        session_destroy();
        header('Location: /supermarket/index.php');
        exit;
    }
}
