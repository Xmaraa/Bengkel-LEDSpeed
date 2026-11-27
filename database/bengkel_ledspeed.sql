-- LED Speed #9thGarage: skema + data contoh (MySQL / MariaDB)
-- Opsi B: import manual lewat phpMyAdmin. Jangan dipakai bersamaan dengan `php artisan migrate`.
-- Jika memakai file ini, set di .env: SESSION_DRIVER=file dan CACHE_STORE=file

CREATE DATABASE IF NOT EXISTS bengkel_ledspeed CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bengkel_ledspeed;

CREATE TABLE users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(15) NULL,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'pelanggan',
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE vehicles (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  jenis VARCHAR(10) NOT NULL,
  plat_nomor VARCHAR(12) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY vehicles_user_plat (user_id, plat_nomor),
  CONSTRAINT vehicles_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE services (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  harga INT UNSIGNED NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE reservations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(20) NOT NULL UNIQUE,
  user_id BIGINT UNSIGNED NOT NULL,
  vehicle_id BIGINT UNSIGNED NOT NULL,
  service_id BIGINT UNSIGNED NOT NULL,
  tanggal DATE NOT NULL,
  jam VARCHAR(5) NOT NULL,
  keluhan TEXT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'menunggu',
  harga_saat_booking INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  KEY reservations_tanggal_jam (tanggal, jam),
  CONSTRAINT rsv_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT rsv_vehicle_fk FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
  CONSTRAINT rsv_service_fk FOREIGN KEY (service_id) REFERENCES services(id)
) ENGINE=InnoDB;

-- Data contoh. Login admin: admin@ledspeed.test / admin12345 ; pelanggan: budi@example.test / password123
INSERT INTO users (id, name, email, phone, password, role, created_at, updated_at) VALUES
 (1, 'Admin LED Speed', 'admin@ledspeed.test', '081200000000', '$2y$10$NzoFUmZuDUZY/2w2kP1amec0ve/g62w9DP2EVZTBMd7.kFVYbV9bK', 'admin', NOW(), NOW()),
 (2, 'Budi Santoso', 'budi@example.test', '081234567890', '$2y$10$Eu8pV3CQa0cYx2XmCD8rKuxTeJ5wwnsaWaPf5xt1gGu4fb3DvCCWO', 'pelanggan', NOW(), NOW());

INSERT INTO services (id, nama, harga, is_active, created_at, updated_at) VALUES
 (1, 'Ganti oli', 85000, 1, NOW(), NOW()),
 (2, 'Servis berkala', 250000, 1, NOW(), NOW()),
 (3, 'Tune up', 180000, 1, NOW(), NOW()),
 (4, 'Ganti kampas rem', 150000, 1, NOW(), NOW()),
 (5, 'Spooring & balancing', 200000, 1, NOW(), NOW()),
 (6, 'Servis AC mobil', 300000, 1, NOW(), NOW());

INSERT INTO vehicles (id, user_id, jenis, plat_nomor, created_at, updated_at) VALUES
 (1, 2, 'motor', 'L 4521 KX', NOW(), NOW());

INSERT INTO reservations (kode, user_id, vehicle_id, service_id, tanggal, jam, keluhan, status, harga_saat_booking, created_at, updated_at) VALUES
 ('RSV-DEMO-0001', 2, 1, 1, CURDATE() + INTERVAL 1 DAY, '09:00', 'Oli sudah 3 bulan', 'dikonfirmasi', 85000, NOW(), NOW()),
 ('RSV-DEMO-0002', 2, 1, 3, CURDATE() - INTERVAL 14 DAY, '10:00', NULL, 'selesai', 180000, NOW(), NOW());
