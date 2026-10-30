-- CLEANGO DATABASE
-- Milestone 2: Database and DAO Layer

CREATE DATABASE IF NOT EXISTS cleango_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cleango_db;


-- 1. USERS
-- Stores user accounts and roles.

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);


-- 2. CLEANER PROFILES
-- Stores additional information about cleaners.

CREATE TABLE cleaner_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    bio TEXT,
    hourly_rate DECIMAL(10,2) NOT NULL,
    location VARCHAR(100),
    experience_years INT DEFAULT 0,

    FOREIGN KEY (user_id) REFERENCES users(id)
);


-- 3. SERVICES
-- Stores available cleaning services.

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL
);


-- 4. CLEANER SERVICES
-- Connects cleaners with the services they offer.
-- Many-to-Many relationship.

CREATE TABLE cleaner_services (
    cleaner_id INT NOT NULL,
    service_id INT NOT NULL,

    PRIMARY KEY (cleaner_id, service_id),

    FOREIGN KEY (cleaner_id)
        REFERENCES cleaner_profiles(id),

    FOREIGN KEY (service_id)
        REFERENCES services(id)
);


-- 5. BOOKINGS
-- Stores cleaning reservations.

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cleaner_id INT NOT NULL,
    service_id INT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    duration INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',

    FOREIGN KEY (user_id)
        REFERENCES users(id),

    FOREIGN KEY (cleaner_id, service_id)
        REFERENCES cleaner_services(cleaner_id, service_id)
);


-- 6. REVIEWS
-- Stores reviews for completed bookings.

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL UNIQUE,
    rating INT NOT NULL,
    comment TEXT,

    FOREIGN KEY (booking_id)
        REFERENCES bookings(id),

    CONSTRAINT chk_review_rating
        CHECK (rating BETWEEN 1 AND 5)
);


-- ==========================================
-- SAMPLE DATA
-- ==========================================

-- Users
-- Password values below are sample hashes, not real login credentials.

INSERT INTO users (name, email, password, role) VALUES
('Amila H.', 'amila@example.com', '$2y$10$abcdefghijklmnopqrstuuVh3eT0cTnSkD6x3I5B1qQ1nXh2rZk3e', 'cleaner'),
('Sara M.', 'sara@example.com', '$2y$10$abcdefghijklmnopqrstuuVh3eT0cTnSkD6x3I5B1qQ1nXh2rZk3e', 'cleaner'),
('Ema K.', 'ema@example.com', '$2y$10$abcdefghijklmnopqrstuuVh3eT0cTnSkD6x3I5B1qQ1nXh2rZk3e', 'customer'),
('Admin', 'admin@example.com', '$2y$10$abcdefghijklmnopqrstuuVh3eT0cTnSkD6x3I5B1qQ1nXh2rZk3e', 'admin');


-- Cleaner profiles

INSERT INTO cleaner_profiles
(user_id, bio, hourly_rate, location, experience_years)
VALUES
(1, 'Experienced cleaner specializing in home cleaning.', 20.00, 'Sarajevo', 3),
(2, 'Professional cleaner offering home and office cleaning.', 25.00, 'Sarajevo', 5);


-- Services

INSERT INTO services (name, description, base_price) VALUES
('Standard Cleaning', 'Regular cleaning of rooms, kitchen and bathroom.', 30.00),
('Deep Cleaning', 'Detailed cleaning of the entire home.', 60.00),
('Office Cleaning', 'Professional cleaning of office spaces.', 50.00),
('Move-in Cleaning', 'Cleaning before moving into a new home.', 70.00);


-- Cleaner services

INSERT INTO cleaner_services (cleaner_id, service_id) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(2, 3),
(2, 4);


-- Bookings

INSERT INTO bookings
(user_id, cleaner_id, service_id, booking_date, booking_time, duration, status)
VALUES
(3, 1, 1, '2026-11-10', '10:00:00', 3, 'pending'),
(3, 2, 2, '2026-11-15', '12:00:00', 4, 'confirmed'),
(3, 1, 2, '2026-10-15', '09:00:00', 3, 'completed');


-- Reviews

INSERT INTO reviews (booking_id, rating, comment) VALUES
(3, 5, 'Excellent cleaning service. Highly recommended!');