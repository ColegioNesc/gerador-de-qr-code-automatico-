-- Schema do Banco de Dados para o Gerador de QR Code Automático
-- MySQL / MariaDB

CREATE DATABASE IF NOT EXISTS `gerador_qrcode` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `gerador_qrcode`;

CREATE TABLE IF NOT EXISTS `qr_codes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code_uuid` VARCHAR(64) NOT NULL UNIQUE,
    `session_id` VARCHAR(128) NOT NULL,
    `content_type` ENUM('url', 'text', 'whatsapp', 'wifi') NOT NULL DEFAULT 'url',
    `content_data` TEXT NOT NULL,
    `fg_color` VARCHAR(16) NOT NULL DEFAULT '#000000',
    `bg_color` VARCHAR(16) NOT NULL DEFAULT '#ffffff',
    `contrast_ratio` DECIMAL(5,2) NOT NULL DEFAULT 21.00,
    `size` INT UNSIGNED NOT NULL DEFAULT 350,
    `format` VARCHAR(8) NOT NULL DEFAULT 'png',
    `ecc_level` VARCHAR(4) NOT NULL DEFAULT 'M',
    `file_path` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_session_id` (`session_id`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
