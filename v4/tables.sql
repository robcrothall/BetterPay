-- BetterPay v4 database schema (initial)
-- Run: mysql -u root -p < tables.sql

CREATE DATABASE IF NOT EXISTS betterpay_v4 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE betterpay_v4;

-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  email VARCHAR(255),
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100),
  surname VARCHAR(100),
  display_name VARCHAR(150),
  phone_mobile VARCHAR(50),
  phone_landline VARCHAR(50),
  is_active TINYINT(1) DEFAULT 1,
  password_reset_token VARCHAR(128) DEFAULT NULL,
  password_reset_expires DATETIME DEFAULT NULL,
  created_by INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_by INT DEFAULT 0,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Roles and user roles
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE user_roles (
  user_id INT NOT NULL,
  role_id INT NOT NULL,
  PRIMARY KEY (user_id, role_id)
);

-- Audit log for actions
CREATE TABLE audit_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  table_name VARCHAR(255) NOT NULL,
  row_id INT NOT NULL,
  action VARCHAR(50) NOT NULL,
  field_name VARCHAR(255),
  old_value TEXT,
  new_value TEXT,
  user_id INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Archive of changes (keeps previous and new values)
CREATE TABLE archive_changes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  table_name VARCHAR(255) NOT NULL,
  row_id INT NOT NULL,
  field_name VARCHAR(255),
  old_value TEXT,
  new_value TEXT,
  changed_by INT DEFAULT 0,
  changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (changed_at)
);

-- Supporting lookup tables
CREATE TABLE id_type (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE title (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE user_identity (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  id_type_id INT NOT NULL,
  id_number VARCHAR(100),
  UNIQUE KEY uq_user_id_id_type (user_id, id_type_id)
);

-- Profiles: intersection table linking users to client/profile types
CREATE TABLE profiles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  profile_type ENUM('business','personal','work_seeker') NOT NULL,
  is_published TINYINT(1) DEFAULT 0,
  suspended_until DATE DEFAULT NULL,
  created_by INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_by INT DEFAULT 0,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Business client details
CREATE TABLE business_client (
  profile_id INT PRIMARY KEY,
  business_name VARCHAR(255) NOT NULL,
  registration_number VARCHAR(100),
  address TEXT,
  company_phone VARCHAR(50),
  tax_number VARCHAR(100),
  notes TEXT
);

-- Personal client details
CREATE TABLE personal_client (
  profile_id INT PRIMARY KEY,
  tax_number VARCHAR(100),
  notes TEXT
);

-- Work seeker details
CREATE TABLE work_seeker (
  profile_id INT PRIMARY KEY,
  dob DATE,
  address TEXT,
  tax_number VARCHAR(100),
  job_title VARCHAR(150),
  job_title_other VARCHAR(150),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bank accounts
CREATE TABLE bank_accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  bank_name VARCHAR(150),
  branch_name VARCHAR(150),
  branch_number VARCHAR(50),
  account_name VARCHAR(150),
  account_number VARCHAR(100),
  account_type ENUM('Current','Savings','Credit') DEFAULT 'Current',
  relationship ENUM('Self','Joint','3rd Party') DEFAULT 'Self'
);

-- Documents (intersection table)
CREATE TABLE documents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  uploaded_by INT DEFAULT 0,
  filename VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100),
  doc_type VARCHAR(100),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Availability
CREATE TABLE availability (
  id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  availability_type ENUM('daily','period') NOT NULL,
  day_of_week TINYINT,
  start_time TIME,
  end_time TIME,
  period_from DATE,
  period_to DATE,
  notes TEXT
);

-- Timesheets
CREATE TABLE timesheets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  period_from DATE,
  period_to DATE,
  date_worked DATE,
  normal_hours DECIMAL(5,2) DEFAULT 0,
  overtime_hours DECIMAL(5,2) DEFAULT 0,
  confirmed TINYINT(1) DEFAULT 0,
  locked_by_admin TINYINT(1) DEFAULT 0
);

-- Invoices
CREATE TABLE invoices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  due_date DATE,
  paid TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Indexes and foreign keys can be added as needed by the implementer.
