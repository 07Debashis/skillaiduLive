-- Skilledu Database Schema
-- Import this in phpMyAdmin: Create database 'skilledu' first, then import

CREATE DATABASE IF NOT EXISTS skilledu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE skilledu;

-- Users table (students + admins)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','student') NOT NULL DEFAULT 'student',
  avatar_url VARCHAR(500) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  description TEXT,
  image_url VARCHAR(500) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Courses
CREATE TABLE IF NOT EXISTS courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  short_description VARCHAR(300),
  description TEXT,
  thumbnail_url VARCHAR(500),
  video_url VARCHAR(500),
  category_id INT DEFAULT NULL,
  instructor VARCHAR(120),
  level ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
  duration_hours DECIMAL(5,1) DEFAULT 0,
  price DECIMAL(10,2) DEFAULT 0,
  published TINYINT(1) DEFAULT 1,
  created_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Enrollments (optional, for student progress)
CREATE TABLE IF NOT EXISTS enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_enroll (user_id, course_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seed categories
INSERT INTO categories (name, slug, description) VALUES
('Web Development','web-development','HTML, CSS, JavaScript, frameworks'),
('Data Science','data-science','Python, ML, analytics'),
('Design','design','UI/UX, Figma, graphics'),
('Business','business','Marketing, finance, entrepreneurship'),
('Mobile Development','mobile-development','iOS, Android, React Native')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed default admin (email: admin@skilledu.com / password: admin123)
-- Hash generated with PHP password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (full_name, email, password_hash, role) VALUES
('Admin','admin@skilledu.com','$2y$10$wH8qYJZ4yX1Y3vQpJ8qYJOeqXk9JZvQpJ8qYJOeqXk9JZvQpJ8qYJ','admin')
ON DUPLICATE KEY UPDATE email=email;

-- Seed sample courses
INSERT INTO courses (title, slug, short_description, description, thumbnail_url, video_url, category_id, instructor, level, duration_hours, price, published) VALUES
('Modern JavaScript Mastery','modern-javascript-mastery','Master ES6+, async, modules, and modern patterns.','Deep-dive into modern JavaScript including ES6+, promises, async/await, modules, and design patterns used in production apps.','https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=800','https://www.youtube.com/watch?v=W6NZfCO5SIk',1,'Sarah Chen','Intermediate',12.5,49.00,1),
('Python for Data Science','python-data-science','NumPy, Pandas, and visualization fundamentals.','Hands-on introduction to data science with Python: NumPy, Pandas, Matplotlib, and Seaborn. Build real datasets end-to-end.','https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800','https://www.youtube.com/watch?v=LHBE6Q9XlzI',2,'Dr. Raj Patel','Beginner',18.0,59.00,1),
('UI/UX Design Foundations','uiux-design-foundations','Color, typography, layout, and Figma workflow.','Learn the fundamentals of user interface and experience design. Includes Figma workflow, design systems, and prototyping.','https://images.unsplash.com/photo-1561070791-2526d30994b8?w=800','https://www.youtube.com/watch?v=c9Wg6Cb_YlU',3,'Emma Rodriguez','Beginner',9.0,39.00,1);
