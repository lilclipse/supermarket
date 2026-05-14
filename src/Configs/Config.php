<?php
namespace App\Configs;

use PDO;

class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'supermarket';
    const DB_USER = 'root';
    const DB_PASS = '';

    public static function getPDO(): PDO
    {
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';

        return new PDO($dsn, self::DB_USER, self::DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function siteUrl(string $page = ''): string
    {
        if ($page === '') {
            return '/supermarket/index.php';
        }

        return '/supermarket/index.php?page=' . urlencode($page);
    }

    public static function redirect(string $page = ''): void
    {
        header('Location: ' . self::siteUrl($page));
        exit;
    }
}
