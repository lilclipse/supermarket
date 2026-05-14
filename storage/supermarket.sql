CREATE DATABASE IF NOT EXISTS supermarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE supermarket;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(30) NOT NULL,
    category VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(160) NOT NULL,
    phone VARCHAR(40) NOT NULL,
    email VARCHAR(120) NOT NULL,
    address TEXT NOT NULL,
    total_sum DECIMAL(10,2) NOT NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'Новый',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    sum_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, description, price, image, category) VALUES
('Молоко 1 л', 'Свежее молоко 3,2% для завтраков и выпечки.', 89.00, '🥛', 'Молочные продукты'),
('Хлеб пшеничный', 'Мягкий свежий хлеб из пшеничной муки.', 45.00, '🍞', 'Выпечка'),
('Яблоки 1 кг', 'Сочные красные яблоки для полезного перекуса.', 120.00, '🍎', 'Фрукты'),
('Сыр 200 г', 'Российский сыр, отлично подходит для бутербродов.', 180.00, '🧀', 'Молочные продукты'),
('Куриное филе 1 кг', 'Охлаждённое куриное филе для домашних блюд.', 340.00, '🍗', 'Мясо'),
('Рис 900 г', 'Крупа длиннозёрная, универсальный гарнир.', 110.00, '🍚', 'Бакалея'),
('Помидоры 1 кг', 'Свежие томаты для салатов и горячих блюд.', 160.00, '🍅', 'Овощи'),
('Сок апельсиновый 1 л', 'Освежающий сок без лишней суеты.', 130.00, '🧃', 'Напитки');
