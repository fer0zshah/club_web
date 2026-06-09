CREATE DATABASE IF NOT EXISTS clubweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clubweb;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  roll_number VARCHAR(50) NOT NULL UNIQUE,
  department VARCHAR(100) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(30) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role  ENUM('student', 'admin') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_type VARCHAR(20) NOT NULL,
  title VARCHAR(255) NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  location VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  host_name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- 2. CONTESTS TABLE
CREATE TABLE IF NOT EXISTS contests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  location VARCHAR(255) NOT NULL, -- e.g., "Lab 4 & Lab 3, 7th Floor" or "Online Platform"
  platform_link VARCHAR(255) DEFAULT NULL, -- Link to Codeforces, Vjudge, etc.
  status ENUM('upcoming', 'ongoing', 'completed') NOT NULL DEFAULT 'upcoming',
  contest_type VARCHAR(100) DEFAULT NULL, -- e.g., "Team Selection", "Individual"
  created_by INT NOT NULL, -- Which admin created this
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS workshops (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  location VARCHAR(255) NOT NULL,
  mentor_name VARCHAR(255) NOT NULL, -- Who is leading the training
  materials_link VARCHAR(255) DEFAULT NULL, -- Drive link or resource URL for study materials
  max_participants INT DEFAULT NULL, -- Optional limit for physical lab space
  created_by INT NOT NULL, -- Which admin created this
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);