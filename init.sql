-- Create database and tables for QRIS Paid Voting

CREATE DATABASE IF NOT EXISTS votingdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE votingdb;

-- Admins
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL
);

-- Candidates
CREATE TABLE IF NOT EXISTS candidates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  photo VARCHAR(255) DEFAULT NULL
);

-- Votes
CREATE TABLE IF NOT EXISTS votes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  candidate_id INT NOT NULL,
  qty INT NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_votes_candidate FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE
);

-- Orders (payment orders via QRIS)
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  order_id VARCHAR(64) NOT NULL UNIQUE,
  qty_votes INT NOT NULL,
  amount INT NOT NULL,
  status ENUM('pending','awaiting_review','approved','rejected','cancelled','paid') NOT NULL DEFAULT 'pending',
  proof_path VARCHAR(255) NULL,
  claimed TINYINT(1) NOT NULL DEFAULT 0,
  approved_by INT NULL,
  approved_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Seed admin user (username: admin, password: admin123)
INSERT INTO admins (username, password)
VALUES ('admin', MD5('admin123'))
ON DUPLICATE KEY UPDATE username = VALUES(username);

-- Example: Insert candidates
-- Run these as examples after creating the database
-- INSERT INTO candidates (name, photo) VALUES ('Candidate 1', 'uploads/cand1.jpg');
-- INSERT INTO candidates (name, photo) VALUES ('Candidate 2', 'uploads/cand2.jpg');


