CREATE DATABASE runaz_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE runaz_app;

-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('requester','runner','admin') DEFAULT 'requester',
  phone VARCHAR(20),
  verified TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services (categories/subcategories)
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  parent_id INT DEFAULT NULL,
  FOREIGN KEY (parent_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Requests (job postings)
CREATE TABLE requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('open','assigned','completed','cancelled') DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Bids (runners apply to requests)
CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  runner_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  message TEXT,
  status ENUM('pending','accepted','rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(id),
  FOREIGN KEY (runner_id) REFERENCES users(id)
);

-- Transactions (escrow/payments)
CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  payer_id INT NOT NULL,
  payee_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending','released','refunded') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(id),
  FOREIGN KEY (payer_id) REFERENCES users(id),
  FOREIGN KEY (payee_id) REFERENCES users(id)
);
