-- evaluation_db

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL  -- เช่น 'admin', 'user', 'evaluator'
);

CREATE TABLE members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  email VARCHAR(100),
  role_id INT NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

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

CREATE TABLE evaluation_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL, 
  description TEXT,
  is_active BOOLEAN DEFAULT TRUE
);



CREATE TABLE activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,             -- ผู้ใช้งาน
  action VARCHAR(100) NOT NULL,       -- เช่น "login", "view_profile", "submit_evaluation"
  target_id INT,                      -- อ้างอิงถึงสมาชิกเป้าหมาย (ถ้ามี)
  ip_address VARCHAR(45),
  user_agent TEXT,
  logged_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (member_id) REFERENCES members(id)
);

CREATE TABLE evaluation_rounds (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,             -- เช่น "Q1/2025", "ประเมินกลางปี"
  start_date DATE,
  end_date DATE,
  is_active BOOLEAN DEFAULT TRUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE evaluations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evaluator_id INT NOT NULL,
  target_id INT NOT NULL,
  round_id INT NOT NULL,                  -- เชื่อมกับ evaluation_rounds
  evaluated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  comment TEXT,
  FOREIGN KEY (evaluator_id) REFERENCES members(id),
  FOREIGN KEY (target_id) REFERENCES members(id),
  FOREIGN KEY (round_id) REFERENCES evaluation_rounds(id)
);

CREATE TABLE evaluation_scores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evaluation_id INT NOT NULL,
  category_id INT NOT NULL,
  score DECIMAL(5,2) NOT NULL,            -- เช่น 4.5 จาก 5
  feedback TEXT,                          -- ข้อเสนอแนะเฉพาะหมวด
  FOREIGN KEY (evaluation_id) REFERENCES evaluations(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES evaluation_categories(id)
);