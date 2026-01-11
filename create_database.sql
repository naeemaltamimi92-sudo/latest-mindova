-- Mindova Database Creation Script
-- Run this in phpMyAdmin or MySQL command line

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS mindova
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Show success message
SELECT 'Database "mindova" created successfully!' as Message;

-- Show all databases to confirm
SHOW DATABASES;
