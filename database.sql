-- Skeem + algandmed
DROP TABLE IF EXISTS loans; DROP TABLE IF EXISTS reservations; DROP TABLE IF EXISTS books; DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  personal_code CHAR(11) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','staff') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  isbn VARCHAR(20) NOT NULL UNIQUE,
  available_copies INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  reserved_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME NOT NULL,
  status ENUM('active','expired','converted') NOT NULL DEFAULT 'active',
  CONSTRAINT fk_res_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_res_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  loan_date DATE NOT NULL,
  due_date DATE NOT NULL,
  return_date DATE NULL,
  CONSTRAINT fk_loan_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_loan_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO users (firstname, lastname, personal_code, email, password, role) VALUES
('Mari','Töötaja','10987654321','staff@example.com','$2y$10$0G.Ht0P6dCFwMv4P2xZBxu6QhPFAxE9sDnNULZ9TYQIxmA3J3cGQK','staff'),
('Mati','Kasutaja','12345678901','user@example.com','$2y$10$0G.Ht0P6dCFwMv4P2xZBxu6QhPFAxE9sDnNULZ9TYQIxmA3J3cGQK','user');
INSERT INTO books (title, author, isbn, available_copies) VALUES
('Tõde ja õigus','A. H. Tammsaare','9789985317439',3),
('Kevade','Oskar Luts','9789985657085',2),
('Rehepapp','Andrus Kivirähk','9789985654954',4),
('Mäeküla piimamees','Eduard Vilde','9789985601873',2),
('Mees, kes teadis ussisõnu','Andrus Kivirähk','9789985656965',3),
('Röövlirahnu Martin','Oskar Luts','9789985805585',1),
('Kõrboja peremees','A. H. Tammsaare','9789985317460',2),
('Põrgupõhja uus Vanapagan','A. H. Tammsaare','9789985317477',2),
('Wiki maailma ajalugu','Eri autorid','9789985499999',5),
('Sipsik','Eno Raud','9789985317002',2);
