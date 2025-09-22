-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS evaluation_db;
USE evaluation_db;

-- ตารางบทบาทผู้ใช้งาน
CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
);

-- ตารางสมาชิก
CREATE TABLE members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- โปรไฟล์สมาชิก
CREATE TABLE profiles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  full_name VARCHAR(100),
  gender ENUM('male', 'female', 'other'),
  birthdate DATE,
  phone VARCHAR(20),
  address TEXT,
  avatar_url VARCHAR(255),
  bio TEXT,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- หมวดหมู่การประเมิน
CREATE TABLE evaluation_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  is_active BOOLEAN DEFAULT TRUE
);

-- บันทึกกิจกรรม
CREATE TABLE activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  action VARCHAR(100) NOT NULL,
  target_id INT,
  ip_address VARCHAR(45),
  user_agent TEXT,
  logged_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (member_id) REFERENCES members(id),
  FOREIGN KEY (target_id) REFERENCES members(id)
);

-- รอบการประเมิน
CREATE TABLE evaluation_rounds (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  start_date DATE,
  end_date DATE,
  is_active BOOLEAN DEFAULT TRUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- การประเมิน
CREATE TABLE evaluations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evaluator_id INT NOT NULL,
  target_id INT NOT NULL,
  round_id INT NOT NULL,
  evaluated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  comment TEXT,
  FOREIGN KEY (evaluator_id) REFERENCES members(id),
  FOREIGN KEY (target_id) REFERENCES members(id),
  FOREIGN KEY (round_id) REFERENCES evaluation_rounds(id)
);

-- คะแนนการประเมิน
CREATE TABLE evaluation_scores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evaluation_id INT NOT NULL,
  category_id INT NOT NULL,
  score DECIMAL(5,2) NOT NULL,
  feedback TEXT,
  FOREIGN KEY (evaluation_id) REFERENCES evaluations(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES evaluation_categories(id)
);

-- ข้อมูลเริ่มต้น
INSERT INTO roles (id, name) VALUES (1, 'Admin'), (2, 'User');

INSERT INTO members (username, email, password_hash, role_id)
VALUES ('admin', 'a@a.com', '$2y$10$bs131mR0tDICaDryZ8NHM.aX/gEm/vn/nawbaOznwc0TIQZIZuAmO', 1);