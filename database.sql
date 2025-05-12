-- Création de la base de données
CREATE DATABASE IF NOT EXISTS hotel_db;
USE hotel_db;


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(25) NOT NULL UNIQUE,
    password VARCHAR(25) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    role ENUM('admin', 'client') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type VARCHAR(50) NOT NULL,
    description TEXT,
    price_per_night DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available'
);


CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);


INSERT INTO users (email, password, first_name, last_name, role) 
VALUES ('admin@hoteldeluxe.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'System', 'admin');


INSERT INTO rooms (room_number, room_type, description, price_per_night, capacity) VALUES
('101', 'Standard', 'Chambre standard confortable avec lit double', 100.00, 2),
('102', 'Deluxe', 'Chambre deluxe avec vue sur la ville', 150.00, 2),
('201', 'Suite', 'Suite luxueuse avec salon', 250.00, 4),
('202', 'Family', 'Chambre familiale spacieuse', 200.00, 6); 