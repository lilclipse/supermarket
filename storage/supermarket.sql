-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 14 2026 г., 21:12
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `supermarket`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(120) NOT NULL,
  `address` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('new','processing','delivery','completed','cancelled') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `phone`, `email`, `address`, `total`, `status`, `created_at`) VALUES
(1, 2, 'Иван Петров', '+7 900 111-22-33', 'user@supermarket.local', 'г. Кемерово, ул. Весенняя, д. 10', 254.00, 'completed', '2026-05-14 19:06:27'),
(2, 2, 'Иван Петров', '+7 900 111-22-33', 'user@supermarket.local', 'г. Кемерово, пр. Ленина, д. 25', 520.00, 'delivery', '2026-05-14 19:06:27');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `sum_item` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `sum_item`) VALUES
(1, 1, 1, 1, 89.00, 89.00),
(2, 1, 2, 1, 45.00, 45.00),
(3, 1, 3, 1, 120.00, 120.00),
(4, 2, 5, 1, 340.00, 340.00),
(5, 2, 4, 1, 180.00, 180.00);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `category` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `stock`, `is_deleted`, `created_at`) VALUES
(1, 'Молоко 1 л', 'Молочные продукты', 'Свежее пастеризованное молоко 3.2%. Подходит для каши, кофе и домашней выпечки.', 89.00, 45, 0, '2026-05-14 19:06:27'),
(2, 'Хлеб пшеничный', 'Выпечка', 'Мягкий пшеничный хлеб с хрустящей корочкой. Выпекается ежедневно.', 45.00, 60, 0, '2026-05-14 19:06:27'),
(3, 'Яблоки красные 1 кг', 'Фрукты', 'Сочные красные яблоки. Хороший выбор для перекуса и десертов.', 120.00, 80, 0, '2026-05-14 19:06:27'),
(4, 'Сыр Российский 200 г', 'Молочные продукты', 'Полутвёрдый сыр с насыщенным сливочным вкусом.', 180.00, 35, 0, '2026-05-14 19:06:27'),
(5, 'Куриное филе 1 кг', 'Мясо и птица', 'Охлаждённое куриное филе без кожи. Подходит для жарки, запекания и салатов.', 340.00, 28, 0, '2026-05-14 19:06:27'),
(6, 'Картофель 1 кг', 'Овощи', 'Свежий картофель среднего размера. Универсальный продукт для повседневных блюд.', 55.00, 100, 0, '2026-05-14 19:06:27'),
(7, 'Сок апельсиновый 1 л', 'Напитки', 'Апельсиновый сок без газа. Освежающий напиток к завтраку.', 130.00, 50, 0, '2026-05-14 19:06:27'),
(8, 'Макароны 450 г', 'Бакалея', 'Макароны из твёрдых сортов пшеницы. Быстро готовятся и хорошо держат форму.', 95.00, 75, 0, '2026-05-14 19:06:27'),
(9, 'Йогурт натуральный 250 г', 'Молочные продукты', 'Нежный натуральный йогурт без лишних добавок. Подходит для завтрака и перекуса.', 75.00, 40, 0, '2026-05-14 19:06:27'),
(10, 'Бананы 1 кг', 'Фрукты', 'Спелые бананы для перекуса, смузи и выпечки.', 110.00, 55, 0, '2026-05-14 19:06:27'),
(11, 'Рис круглозёрный 900 г', 'Бакалея', 'Крупа для каш, гарниров и домашних блюд.', 140.00, 45, 0, '2026-05-14 19:06:27'),
(12, 'Чай чёрный 100 пак.', 'Напитки', 'Классический чёрный чай для дома и офиса.', 210.00, 30, 0, '2026-05-14 19:06:27');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@supermarket.local', '$2y$12$GsQoBnikzN1s50L7cqguG.Z5znN03f.31hO3vEWTf3mo.eMFP02jO', 'admin', '2026-05-14 19:06:27'),
(2, 'user', 'user@supermarket.local', '$2y$12$GsQoBnikzN1s50L7cqguG.Z5znN03f.31hO3vEWTf3mo.eMFP02jO', 'user', '2026-05-14 19:06:27');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_index` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_product_id_index` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
