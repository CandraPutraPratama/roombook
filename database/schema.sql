-- Database schema for RoomBook
-- Sistem Reservasi Ruangan Kampus

DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS rooms;

CREATE TABLE rooms (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(150) NOT NULL,
    capacity INTEGER NOT NULL,
    facilities TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT rooms_status_check 
    CHECK (status IN ('available', 'maintenance', 'unavailable')),

    CONSTRAINT rooms_capacity_check 
    CHECK (capacity > 0)
);

CREATE TABLE reservations (
    id SERIAL PRIMARY KEY,
    room_id INTEGER NOT NULL,
    borrower_name VARCHAR(100) NOT NULL,
    borrower_contact VARCHAR(100) NOT NULL,
    activity_name VARCHAR(150) NOT NULL,
    reservation_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    purpose TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_reservations_room
    FOREIGN KEY (room_id)
    REFERENCES rooms(id)
    ON DELETE CASCADE,

    CONSTRAINT reservations_status_check
    CHECK (status IN ('pending', 'approved', 'rejected', 'completed')),

    CONSTRAINT reservations_time_check
    CHECK (end_time > start_time)
);