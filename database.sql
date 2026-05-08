CREATE DATABASE IF NOT EXISTS skill_gap_db;
USE skill_gap_db;

DROP TABLE IF EXISTS analyses;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE analyses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    dream_job VARCHAR(200) NOT NULL,
    current_skills TEXT NOT NULL,
    missing_skills TEXT,
    roadmap TEXT,
    match_percent INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Default admin (password: admin123)
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@skillgap.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Sample user (password: test123)
INSERT INTO users (name, email, password_hash, role) VALUES
('Rahul Kumar', 'rahul@example.com', '$2y$10$TKh8H1.PfuG1/TJ8YKBMuOqCQMPQjg6VSXM8Ev3OYcO2Ln2OKy8Ei', 'user');
