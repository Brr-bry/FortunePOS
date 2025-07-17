-- Create the database
CREATE DATABASE IF NOT EXISTS fortune_pos;
USE fortune_pos;

-- USERS TABLE
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('Admin', 'Staff') NOT NULL
);

-- Insert default admin and staff users
INSERT INTO users (username, password, role) VALUES
('admin', '123', 'Admin'),
('cashier1', '123', 'Staff');

-- PRODUCTS TABLE (formerly inventory)
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  stock INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL
);

-- Optional: Pre-insert sample products
INSERT INTO products (name, stock, price) VALUES
('T-Shirt', 4, 100.00),
('T-Shirt1', 2, 400.00);

-- ORDERS TABLE
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
