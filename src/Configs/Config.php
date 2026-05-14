<?php

namespace App\Configs;

use PDO;

class Config
{
    public const DB_HOST = 'localhost';
    public const DB_NAME = 'supermarket';
    public const DB_USER = 'root';
    public const DB_PASS = '';

    public static function getPDO(): PDO
    {
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';

        return new PDO($dsn, self::DB_USER, self::DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
