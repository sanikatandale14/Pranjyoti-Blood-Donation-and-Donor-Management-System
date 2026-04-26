-- Blood Bank & Donor Management System Database
-- Database Name: pranjyoti_db

CREATE DATABASE IF NOT EXISTS pranjyoti_db;
USE pranjyoti_db;

-- Users Table (User accounts - both regular users and admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    blood_group VARCHAR(5),
    city VARCHAR(50),
    user_type ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blood Groups Table
CREATE TABLE IF NOT EXISTS blood_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Donors Table
CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    gender VARCHAR(10),
    age INT,
    city VARCHAR(50) NOT NULL,
    address TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    is_available TINYINT(1) DEFAULT 1,
    last_donation_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Blood Requests Table
CREATE TABLE IF NOT EXISTS blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    patient_age INT,
    blood_group VARCHAR(10) NOT NULL,
    units_needed INT DEFAULT 1,
    hospital_name VARCHAR(150),
    hospital_address TEXT,
    contact_name VARCHAR(100) NOT NULL,
    contact_phone VARCHAR(20) NOT NULL,
    reason TEXT,
    urgency ENUM('normal', 'urgent', 'critical') DEFAULT 'normal',
    status ENUM('pending', 'approved', 'fulfilled', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact Queries Table
CREATE TABLE IF NOT EXISTS contact_queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pages Content Table (For dynamic About/Contact pages)
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(50) UNIQUE NOT NULL,
    page_title VARCHAR(200),
    page_content TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Default Blood Groups
INSERT INTO blood_groups (name) VALUES ('A+'), ('A-'), ('B+'), ('B-'), ('AB+'), ('AB-'), ('O+'), ('O-');

-- Insert Default Pages
INSERT INTO pages (page_name, page_title, page_content) VALUES 
('about', 'About Pranjyoti Blood Bank', '<h2>Welcome to Pranjyoti - Save Lives with the Drop of Your Blood</h2><p>We are dedicated to connecting blood donors with those in need. Our platform makes it easy to find donors and manage blood requests efficiently.</p><p>Every year, millions of people need blood transfusions due to surgeries, accidents, or medical conditions. Your donation can save up to three lives.</p>'),
('contact', 'Contact Pranjyoti', '<h2>Contact Information</h2><p><strong>Email:</strong> info@pranjyoti.org</p><p><strong>Phone:</strong> +91 9876543210</p><p><strong>Address:</strong> Blood Bank Center, Medical Road, City - 123456</p>');

-- Insert Admin User (password: admin123)
INSERT INTO users (full_name, email, password, user_type) VALUES 
('Administrator', 'admin@pranjyoti.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');