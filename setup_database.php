#!/usr/bin/env php
<?php
/**
 * MediaLAN Migration Executor
 * Directly creates database tables without Laravel framework
 */

// Configuration
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'medialan');

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║         MediaLAN - Database Setup                      ║\n";
echo "║         (Bootstrap without full Laravel)               ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

// Connect
try {
    echo "[1/6] Connecting to MySQL...\n";
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT,
        DB_USERNAME,
        DB_PASSWORD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "      ✓ Connected\n\n";
} catch (PDOException $e) {
    die("✗ Connection failed: " . $e->getMessage() . "\n");
}

// Create database
try {
    echo "[2/6] Creating database '" . DB_NAME . "'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "      ✓ Database ready\n\n";
} catch (PDOException $e) {
    die("✗ Failed: " . $e->getMessage() . "\n");
}

// Select database
try {
    $pdo->exec("USE `" . DB_NAME . "`");
} catch (PDOException $e) {
    die("✗ Failed to select database: " . $e->getMessage() . "\n");
}

// Table: categories
try {
    echo "[3/6] Creating 'categories' table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `categories` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            INDEX `idx_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "      ✓ Table created\n\n";
} catch (PDOException $e) {
    die("✗ Failed: " . $e->getMessage() . "\n");
}

// Table: media
try {
    echo "[4/6] Creating 'media' table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `media` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `category_id` BIGINT UNSIGNED NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `file_path` VARCHAR(255) NOT NULL UNIQUE,
            `file_size` BIGINT UNSIGNED,
            `duration` INT UNSIGNED COMMENT 'Duration in seconds',
            `poster_url` VARCHAR(255),
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            INDEX `idx_category` (`category_id`),
            INDEX `idx_created` (`created_at`),
            FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "      ✓ Table created\n\n";
} catch (PDOException $e) {
    die("✗ Failed: " . $e->getMessage() . "\n");
}

// Table: pins
try {
    echo "[5/6] Creating 'pins', 'downloads_log', and 'admin_users' tables...\n";
    
    // pins table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `pins` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `media_id` BIGINT UNSIGNED NOT NULL,
            `pin_code` VARCHAR(10) NOT NULL UNIQUE,
            `max_downloads` INT UNSIGNED DEFAULT 0 COMMENT '0 = unlimited',
            `downloads_count` INT UNSIGNED DEFAULT 0,
            `expires_at` TIMESTAMP NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            INDEX `idx_media` (`media_id`),
            INDEX `idx_pin` (`pin_code`),
            FOREIGN KEY (`media_id`) REFERENCES `media`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // downloads_log table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `downloads_log` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `media_id` BIGINT UNSIGNED NOT NULL,
            `pin_id` BIGINT UNSIGNED,
            `ip_address` VARCHAR(45),
            `downloaded_at` TIMESTAMP NULL,
            `file_size` BIGINT UNSIGNED,
            INDEX `idx_media` (`media_id`),
            INDEX `idx_pin` (`pin_id`),
            INDEX `idx_downloaded` (`downloaded_at`),
            FOREIGN KEY (`media_id`) REFERENCES `media`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`pin_id`) REFERENCES `pins`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // admin_users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `admin_users` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `username` VARCHAR(255) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL,
            INDEX `idx_username` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "      ✓ All tables created\n\n";
} catch (PDOException $e) {
    die("✗ Failed: " . $e->getMessage() . "\n");
}

// Seed initial data
try {
    echo "[6/6] Seeding initial data...\n";
    
    // Categories
    $categories = [
        ['Movies', 'Feature films and movies'],
        ['TV Series', 'Television shows and series'],
        ['Documentaries', 'Documentary content'],
        ['Educational', 'Educational and learning content'],
        ['Music', 'Music videos and performances'],
        ['Sports', 'Sports events and highlights'],
        ['Podcasts', 'Podcast episodes'],
        ['Other', 'Other content'],
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `categories` (`name`, `description`, `created_at`, `updated_at`) VALUES (?, ?, NOW(), NOW())");
    foreach ($categories as [$name, $desc]) {
        $stmt->execute([$name, $desc]);
    }
    echo "      ✓ {" . count($categories) . "} categories created\n";
    
    // Admin user
    $password = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO `admin_users` (`name`, `username`, `password`, `created_at`, `updated_at`) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->execute(['Administrator', 'admin', $password]);
    echo "      ✓ Admin user created (username: admin, password: admin123)\n\n";
    
} catch (PDOException $e) {
    die("✗ Seed failed: " . $e->getMessage() . "\n");
}

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║ ✓ Database setup completed successfully!              ║\n";
echo "╠════════════════════════════════════════════════════════╣\n";
echo "║ Database: " . DB_NAME . "\n";
echo "║ Tables: 5 (categories, media, pins, downloads_log, admin_users)\n";
echo "║ Admin Login: admin / admin123\n";
echo "║\n";
echo "║ Next Steps:\n";
echo "║   1. Verify database in phpMyAdmin: http://localhost/phpmyadmin\n";
echo "║   2. Wait for Composer installation to complete\n";
echo "║   3. Run: php artisan serve --host=0.0.0.0 --port=8000\n";
echo "║   4. Access from phone: http://[YOUR_IP]:8000\n";
echo "╚════════════════════════════════════════════════════════╝\n";
