-- Готовая база данных для ИС «Супермаркет»
-- Импортируйте файл в phpMyAdmin.
-- Тестовые аккаунты:
-- admin / 123456
-- user / 123456

CREATE DATABASE IF NOT EXISTS `supermarket`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `supermarket`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(80) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `category` VARCHAR(80) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `customer_name` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `address` TEXT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `status` ENUM('new', 'processing', 'delivery', 'completed', 'cancelled') NOT NULL DEFAULT 'new',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_index` (`user_id`),
  CONSTRAINT `orders_user_id_fk`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `price` DECIMAL(10,2) NOT NULL,
  `sum_item` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`),
  CONSTRAINT `order_items_order_id_fk`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `order_items_product_id_fk`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@supermarket.local', '$2y$10$8nQmMa5gB5O5bQ81VN6m/.xK7e/hxe81BQNQ1xmLfcY98U8UK5XyG', 'admin'),
(2, 'user', 'user@supermarket.local', '$2y$10$8nQmMa5gB5O5bQ81VN6m/.xK7e/hxe81BQNQ1xmLfcY98U8UK5XyG', 'user');

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `stock`, `is_deleted`) VALUES
(1, 'Молоко 1 л', 'Молочные продукты', 'Свежее пастеризованное молоко 3.2%. Подходит для каши, кофе и домашней выпечки.', 89.00, 45, 0),
(2, 'Хлеб пшеничный', 'Выпечка', 'Мягкий пшеничный хлеб с хрустящей корочкой. Выпекается ежедневно.', 45.00, 60, 0),
(3, 'Яблоки красные 1 кг', 'Фрукты', 'Сочные красные яблоки. Хороший выбор для перекуса и десертов.', 120.00, 80, 0),
(4, 'Сыр Российский 200 г', 'Молочные продукты', 'Полутвёрдый сыр с насыщенным сливочным вкусом.', 180.00, 35, 0),
(5, 'Куриное филе 1 кг', 'Мясо и птица', 'Охлаждённое куриное филе без кожи. Подходит для жарки, запекания и салатов.', 340.00, 28, 0),
(6, 'Картофель 1 кг', 'Овощи', 'Свежий картофель среднего размера. Универсальный продукт для повседневных блюд.', 55.00, 100, 0),
(7, 'Сок апельсиновый 1 л', 'Напитки', 'Апельсиновый сок без газа. Освежающий напиток к завтраку.', 130.00, 50, 0),
(8, 'Макароны 450 г', 'Бакалея', 'Макароны из твёрдых сортов пшеницы. Быстро готовятся и хорошо держат форму.', 95.00, 75, 0);

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `phone`, `email`, `address`, `total`, `status`) VALUES
(1, 2, 'Иван Петров', '+7 900 111-22-33', 'user@supermarket.local', 'г. Кемерово, ул. Весенняя, д. 10', 254.00, 'completed');

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `sum_item`) VALUES
(1, 1, 1, 1, 89.00, 89.00),
(2, 1, 2, 1, 45.00, 45.00),
(3, 1, 3, 1, 120.00, 120.00);

COMMIT;
