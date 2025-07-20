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

    INSERT INTO `products` (`id`, `name`, `stock`, `price`) VALUES
    (1, 'Basic White T-Shirt', 50, 199.99),
    (2, 'Black Slim Fit Jeans', 30, 899.00),
    (3, 'Blue Denim Jacket', 20, 1200.00),
    (4, 'Graphic Hoodie', 25, 850.50),
    (5, 'Plain Gray Sweatpants', 40, 500.00),
    (6, 'Cotton Polo Shirt', 35, 450.00),
    (7, 'Oversized Crewneck', 18, 699.99),
    (8, 'Flannel Shirt', 22, 599.00),
    (9, 'Cargo Pants', 28, 749.00),
    (10, 'Drawstring Shorts', 32, 399.00),
    (11, 'Long Sleeve Tee', 27, 350.00),
    (12, 'Denim Shorts', 19, 650.00),
    (13, 'Athletic Joggers', 30, 720.00),
    (14, 'Plain Black Hoodie', 26, 870.00),
    (15, 'Striped Polo', 20, 495.00),
    (16, 'Windbreaker Jacket', 15, 1050.00),
    (17, 'Sweat Shorts', 29, 380.00),
    (18, 'Compression Shirt', 12, 399.00),
    (19, 'Running Shorts', 16, 299.00),
    (20, 'Winter Coat', 10, 2000.00),
    (21, 'V-neck Tee', 33, 249.00),
    (22, 'Knitted Sweater', 14, 990.00),
    (23, 'Linen Pants', 17, 650.00),
    (24, 'Sleeveless Top', 21, 299.00),
    (25, 'Harem Pants', 11, 720.00),
    (26, 'Lightweight Cardigan', 13, 880.00),
    (27, 'Cropped Hoodie', 15, 775.00),
    (28, 'Turtleneck Shirt', 18, 540.00),
    (29, 'Plaid Skirt', 16, 680.00),
    (30, 'Formal Blazer', 9, 1500.00);

    DROP TABLE IF EXISTS order_items;
    DROP TABLE IF EXISTS orders;

    CREATE TABLE orders (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    amount_due DECIMAL(10,2) NOT NULL,
    amount_received DECIMAL(10,2) NOT NULL,
    change_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(20) NOT NULL
    );

    CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_name VARCHAR(100),
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
    );

    -- INSERT INTO orders (total, amount_due, amount_received, change_amount, payment_method)
    INSERT INTO orders (total, amount_due, amount_received, change_amount, payment_method) VALUES
    (199.99, 219.99, 220.00, 0.01, 'Cash'),
    (1099.00, 1208.90, 1208.90, 0.00, 'Gcash'),
    (1650.00, 1815.00, 2000.00, 185.00, 'Cash'),
    (850.50, 935.55, 935.55, 0.00, 'Gcash'),
    (1800.00, 1980.00, 2000.00, 20.00, 'Cash'),
    (995.00, 1094.50, 1200.00, 105.50, 'Cash'),
    (720.00, 792.00, 792.00, 0.00, 'Gcash'),
    (1494.00, 1643.40, 1700.00, 56.60, 'Cash'),
    (870.00, 957.00, 957.00, 0.00, 'Gcash'),
    (850.00, 935.00, 1000.00, 65.00, 'Cash'),
    (1800.00, 1980.00, 1980.00, 0.00, 'Gcash'),
    (999.00, 1098.90, 1200.00, 101.10, 'Cash'),
    (1420.00, 1562.00, 1562.00, 0.00, 'Gcash'),
    (1770.00, 1947.00, 2000.00, 53.00, 'Cash'),
    (1050.00, 1155.00, 1200.00, 45.00, 'Cash'),
    (2200.00, 2420.00, 2420.00, 0.00, 'Gcash'),
    (720.00, 792.00, 800.00, 8.00, 'Cash'),
    (1299.00, 1428.90, 1500.00, 71.10, 'Cash'),
    (800.00, 880.00, 880.00, 0.00, 'Gcash'),
    (1325.00, 1457.50, 1500.00, 42.50, 'Cash'),
    (399.00, 438.90, 500.00, 61.10, 'Cash'),
    (1090.00, 1199.00, 1200.00, 1.00, 'Cash'),
    (1080.00, 1188.00, 1188.00, 0.00, 'Gcash'),
    (870.00, 957.00, 1000.00, 43.00, 'Cash'),
    (650.00, 715.00, 715.00, 0.00, 'Gcash'),
    (720.00, 792.00, 800.00, 8.00, 'Cash'),
    (990.00, 1089.00, 1100.00, 11.00, 'Cash'),
    (880.00, 968.00, 1000.00, 32.00, 'Cash'),
    (1590.00, 1749.00, 1800.00, 51.00, 'Cash'),
    (950.00, 1045.00, 1045.00, 0.00, 'Gcash');

    -- INSERT INTO order_items (order_id, product_name, quantity, price)
    INSERT INTO order_items (order_id, product_name, quantity, price) VALUES
    (1, 'Basic White T-Shirt', 1, 199.99),
    (2, 'Black Slim Fit Jeans', 1, 899.00),
    (2, 'Long Sleeve Tee', 1, 200.00),
    (3, 'Blue Denim Jacket', 1, 1200.00),
    (3, 'Drawstring Shorts', 1, 450.00),
    (4, 'Graphic Hoodie', 1, 850.50),
    (5, 'Cotton Polo Shirt', 2, 450.00),
    (5, 'Plain Gray Sweatpants', 1, 500.00),
    (6, 'Striped Polo', 1, 495.00),
    (6, 'Athletic Joggers', 1, 500.00),
    (7, 'Compression Shirt', 2, 360.00),
    (8, 'Cargo Pants', 2, 747.00),
    (9, 'Plain Black Hoodie', 1, 870.00),
    (10, 'Graphic Hoodie', 1, 850.00),
    (11, 'Cotton Polo Shirt', 2, 450.00),
    (11, 'Drawstring Shorts', 1, 900.00),
    (12, 'Black Slim Fit Jeans', 1, 899.00),
    (12, 'Long Sleeve Tee', 1, 100.00),
    (13, 'Oversized Crewneck', 2, 710.00),
    (14, 'Basic White T-Shirt', 3, 590.00),
    (15, 'Windbreaker Jacket', 1, 1050.00),
    (16, 'Winter Coat', 1, 2000.00),
    (17, 'Athletic Joggers', 1, 720.00),
    (18, 'Flannel Shirt', 2, 650.00),
    (19, 'Knitted Sweater', 1, 800.00),
    (20, 'Cropped Hoodie', 2, 660.00),
    (21, 'Drawstring Shorts', 1, 399.00),
    (22, 'Linen Pants', 1, 650.00),
    (22, 'V-neck Tee', 1, 440.00),
    (23, 'Plain Black Hoodie', 1, 870.00),
    (24, 'Denim Shorts', 1, 650.00),
    (25, 'Harem Pants', 1, 720.00),
    (26, 'Knitted Sweater', 1, 990.00),
    (27, 'Lightweight Cardigan', 1, 880.00),
    (28, 'Striped Polo', 2, 795.00),
    (29, 'Sweat Shorts', 2, 475.00),
    (30, 'Formal Blazer', 1, 950.00);
