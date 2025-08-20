CREATE DATABASE IF NOT EXISTS raamatukogu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE raamatukogu;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  personal_code CHAR(11) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','staff') NOT NULL DEFAULT 'user',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS authors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  isbn VARCHAR(20) UNIQUE,
  total_copies INT NOT NULL DEFAULT 1,
  available_copies INT NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS book_authors (
  book_id INT NOT NULL,
  author_id INT NOT NULL,
  PRIMARY KEY (book_id, author_id),
  FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
  FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  start_date DATE NOT NULL,
  due_date DATE NOT NULL,
  returned_date DATE,
  status ENUM('active','returned','overdue') NOT NULL DEFAULT 'active',
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME NOT NULL,
  status ENUM('active','cancelled','expired','fulfilled') NOT NULL DEFAULT 'active',
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (book_id) REFERENCES books(id),
  INDEX (user_id, book_id, status)
);

CREATE TABLE IF NOT EXISTS auth_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  user_agent VARCHAR(255),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- test users (password = Password123)
INSERT INTO users (first_name,last_name,personal_code,email,password_hash,role) VALUES
('Mari','Kask','47101010012','user@example.com', '$2y$10$g1p8o1M0yqk4q0F8Zl3jFev2g7e8b1.1o5Qk3iVJ3cN7LxjQk6U3i','user')
ON DUPLICATE KEY UPDATE email=email;

INSERT INTO users (first_name,last_name,personal_code,email,password_hash,role) VALUES
('Peeter','Tamm','38101010026','staff@example.com', '$2y$10$g1p8o1M0yqk4q0F8Zl3jFev2g7e8b1.1o5Qk3iVJ3cN7LxjQk6U3i','staff')
ON DUPLICATE KEY UPDATE email=email;

INSERT INTO authors (name) VALUES ('J. K. Rowling'), ('Mark Twain') ON DUPLICATE KEY UPDATE name=name;
INSERT INTO books (title,isbn,total_copies,available_copies) VALUES
('Harry Potter and the Philosopher''s Stone','9780747532743',3,3),
('Adventures of Huckleberry Finn','9780142437179',2,2)
ON DUPLICATE KEY UPDATE title=VALUES(title);
INSERT IGNORE INTO book_authors (book_id, author_id) VALUES (1,1),(2,2);
