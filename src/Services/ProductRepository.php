<?php

namespace App\Services;

use App\Configs\Config;
use Throwable;

class ProductRepository
{
    public static function all(): array
    {
        try {
            $pdo = Config::getPDO();
            $stmt = $pdo->query('SELECT id, name, description, price, image, category FROM products ORDER BY id');
            $products = $stmt->fetchAll();

            if (!empty($products)) {
                return $products;
            }
        } catch (Throwable $e) {
            // Если база ещё не создана, сайт работает в демо-режиме.
        }

        return self::fallbackProducts();
    }

    public static function find(int $id): ?array
    {
        foreach (self::all() as $product) {
            if ((int)$product['id'] === $id) {
                return $product;
            }
        }

        return null;
    }

    public static function fallbackProducts(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Молоко 1 л',
                'description' => 'Свежее молоко 3,2% для завтраков и выпечки.',
                'price' => 89.00,
                'image' => '🥛',
                'category' => 'Молочные продукты',
            ],
            [
                'id' => 2,
                'name' => 'Хлеб пшеничный',
                'description' => 'Мягкий свежий хлеб из пшеничной муки.',
                'price' => 45.00,
                'image' => '🍞',
                'category' => 'Выпечка',
            ],
            [
                'id' => 3,
                'name' => 'Яблоки 1 кг',
                'description' => 'Сочные красные яблоки для полезного перекуса.',
                'price' => 120.00,
                'image' => '🍎',
                'category' => 'Фрукты',
            ],
            [
                'id' => 4,
                'name' => 'Сыр 200 г',
                'description' => 'Российский сыр, отлично подходит для бутербродов.',
                'price' => 180.00,
                'image' => '🧀',
                'category' => 'Молочные продукты',
            ],
            [
                'id' => 5,
                'name' => 'Куриное филе 1 кг',
                'description' => 'Охлаждённое куриное филе для домашних блюд.',
                'price' => 340.00,
                'image' => '🍗',
                'category' => 'Мясо',
            ],
            [
                'id' => 6,
                'name' => 'Рис 900 г',
                'description' => 'Крупа длиннозёрная, универсальный гарнир.',
                'price' => 110.00,
                'image' => '🍚',
                'category' => 'Бакалея',
            ],
            [
                'id' => 7,
                'name' => 'Помидоры 1 кг',
                'description' => 'Свежие томаты для салатов и горячих блюд.',
                'price' => 160.00,
                'image' => '🍅',
                'category' => 'Овощи',
            ],
            [
                'id' => 8,
                'name' => 'Сок апельсиновый 1 л',
                'description' => 'Освежающий сок без лишней суеты.',
                'price' => 130.00,
                'image' => '🧃',
                'category' => 'Напитки',
            ],
        ];
    }
}
