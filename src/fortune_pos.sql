CREATE DATABASE IF NOT EXISTS fortune_pos;
USE fortune_pos;

-- USERS TABLE
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('Admin', 'Staff') NOT NULL
);

INSERT INTO users (username, password, role) VALUES
('admin', '123', 'Admin'),
('cashier1', '123', 'Staff'),
('cashier2', '123', 'Staff'),
('cashier3', '123', 'Staff'),
('cashier4', '123', 'Staff'),
('cashier5', '123', 'Staff'),
('cashier6', '123', 'Staff'),
('cashier7', '123', 'Staff'),
('cashier8', '123', 'Staff'),
('cashier9', '123', 'Staff'),
('cashier10', '123', 'Staff'),
('cashier11', '123', 'Staff'),
('cashier12', '123', 'Staff'),
('cashier13', '123', 'Staff'),
('cashier14', '123', 'Staff'),
('cashier15', '123', 'Staff'),
('cashier16', '123', 'Staff'),
('cashier17', '123', 'Staff'),
('cashier18', '123', 'Staff'),
('cashier19', '123', 'Staff');

-- PRODUCTS TABLE
DROP TABLE IF EXISTS products;
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  stock INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL
);

INSERT INTO products (name, stock, price) VALUES
('T-Shirt Black', 10, 150.00),
('T-Shirt White', 8, 145.00),
('T-Shirt Blue', 12, 160.00),
('Jeans Slim Fit', 6, 800.00),
('Jeans Regular Fit', 9, 750.00),
('Denim Jacket', 4, 1200.00),
('Hoodie Grey', 7, 950.00),
('Hoodie Black', 5, 980.00),
('Cap Red', 15, 120.00),
('Cap Black', 13, 130.00),
('Sneakers Low', 3, 1800.00),
('Sneakers High', 4, 2000.00),
('Socks White', 20, 80.00),
('Socks Black', 18, 85.00),
('Belt Leather', 11, 500.00),
('Backpack Navy', 5, 1100.00),
('Sling Bag', 8, 950.00),
('Wristwatch', 4, 2500.00),
('Sunglasses', 10, 650.00),
('Wallet Brown', 9, 450.00),
('Wallet Black', 7, 480.00),
('Shorts Beige', 6, 490.00),
('Polo Shirt', 5, 600.00),
('Tank Top', 12, 300.00),
('Scarf', 3, 200.00);

-- ORDERS + ORDER_ITEMS TABLES
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  total DECIMAL(10,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  product_name VARCHAR(100),
  quantity INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- INSERT 20 ORDERS
INSERT INTO orders (total, created_at) VALUES
(455.00, NOW()), (800.00, NOW()), (1200.00, NOW()), (950.00, NOW()), (390.00, NOW()),
(1680.00, NOW()), (450.00, NOW()), (600.00, NOW()), (980.00, NOW()), (480.00, NOW()),
(150.00, NOW()), (1240.00, NOW()), (960.00, NOW()), (750.00, NOW()), (240.00, NOW()),
(1400.00, NOW()), (320.00, NOW()), (650.00, NOW()), (300.00, NOW()), (1800.00, NOW());

-- INSERT ORDER ITEMS LINKED TO EACH ORDER
INSERT INTO order_items (order_id, product_name, quantity, price) VALUES
(1, 'T-Shirt Black', 1, 150.00),
(1, 'T-Shirt White', 1, 145.00),
(1, 'T-Shirt Blue', 1, 160.00),

(2, 'Jeans Slim Fit', 1, 800.00),

(3, 'Denim Jacket', 1, 1200.00),

(4, 'Hoodie Grey', 1, 950.00),

(5, 'Cap Black', 3, 130.00),

(6, 'Sneakers Low', 1, 1800.00),

(7, 'Wallet Brown', 1, 450.00),

(8, 'Polo Shirt', 1, 600.00),

(9, 'Hoodie Black', 1, 980.00),

(10, 'Wallet Black', 1, 480.00),

(11, 'T-Shirt Black', 1, 150.00),

(12, 'Backpack Navy', 1, 1100.00),
(12, 'Socks Black', 2, 70.00),

(13, 'Wallet Black', 2, 480.00),

(14, 'Jeans Regular Fit', 1, 750.00),

(15, 'Socks White', 3, 80.00),

(16, 'Sneakers High', 1, 2000.00),
(16, 'Socks White', 2, 80.00),

(17, 'T-Shirt Blue', 2, 160.00),

(18, 'Sunglasses', 1, 650.00),

(19, 'Tank Top', 1, 300.00),

(20, 'Sneakers Low', 1, 1800.00);
