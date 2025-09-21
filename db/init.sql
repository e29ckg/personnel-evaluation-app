CREATE TABLE IF NOT EXISTS members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL DEFAULT 2,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS roles (
  id INT PRIMARY KEY,
  name VARCHAR(50)
);

INSERT INTO roles (id, name) VALUES (1, 'Admin'), (2, 'User');

INSERT INTO `members` (`id`, `username`, `email`, `password_hash`, `role_id`, `created_at`) VALUES (NULL, 'admin', 'a@a.com', '$2y$10$bs131mR0tDICaDryZ8NHM.aX/gEm/vn/nawbaOznwc0TIQZIZuAmO', '2', CURRENT_TIMESTAMP);