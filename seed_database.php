<?php
/**
 * MediaLAN - Quick Seed Script
 */

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║         MediaLAN - Database Seeding                    ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->exec('USE medialan');
    echo "[✓] Connected to database\n\n";
} catch (PDOException $e) {
    die("[✗] Connection failed: " . $e->getMessage() . "\n");
}

// Seed categories
echo "[1/2] Seeding categories...\n";
$categories = [
    ['name' => 'Movies', 'slug' => 'movies', 'description' => 'Feature films and movies'],
    ['name' => 'TV Series', 'slug' => 'tv-series', 'description' => 'Television shows and series'],
    ['name' => 'Documentaries', 'slug' => 'documentaries', 'description' => 'Documentary content'],
    ['name' => 'Educational', 'slug' => 'educational', 'description' => 'Educational and learning content'],
    ['name' => 'Music', 'slug' => 'music', 'description' => 'Music videos and performances'],
    ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports events and highlights'],
    ['name' => 'Podcasts', 'slug' => 'podcasts', 'description' => 'Podcast episodes'],
    ['name' => 'Other', 'slug' => 'other', 'description' => 'Other content'],
];

try {
    // Check if categories already seeded
    $count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    if ($count > 0) {
        echo "      → Categories already seeded ({$count} found)\n";
    } else {
        $stmt = $pdo->prepare("INSERT INTO `categories` (`name`, `slug`, `description`, `created_at`, `updated_at`) VALUES (?, ?, ?, NOW(), NOW())");
        foreach ($categories as $cat) {
            $stmt->execute([$cat['name'], $cat['slug'], $cat['description']]);
        }
        echo "      ✓ {" . count($categories) . "} categories created\n";
    }
} catch (PDOException $e) {
    echo "[✗] Category seed failed: " . $e->getMessage() . "\n";
    echo "    SQL: INSERT INTO categories...\\n";
}

// Seed admin user
echo "[2/2] Seeding admin user...\n";

try {
    // Check if admin exists
    $existing = $pdo->query("SELECT id FROM users WHERE username = 'admin' OR email = 'admin@medialn.local'")->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        echo "      → Admin user already exists\n";
    } else {
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO `users` (`name`, `username`, `email`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            'Administrator',
            'admin',
            'admin@medialn.local',
            $password,
            true
        ]);
        echo "      ✓ Admin user created\n";
        echo "        Username: admin\n";
        echo "        Password: admin123\n";
    }
} catch (PDOException $e) {
    echo "[✗] Admin seed failed: " . $e->getMessage() . "\n";
    // Check if users table has different structure
    try {
        $cols = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
        echo "\n      Users table columns:\n";
        foreach ($cols as $col) {
            echo "        - {$col['Field']}: {$col['Type']}\n";
        }
    } catch (Exception $e2) {
        echo "      Could not inspect users table\n";
    }
}

echo "\n╔════════════════════════════════════════════════════════╗\n";
echo "║ ✓ Seeding completed!                                   ║\n";
echo "╠════════════════════════════════════════════════════════╣\n";
echo "║ Next Steps:                                            ║\n";
echo "║   1. Wait for Composer to finish downloading           ║\n";
echo "║   2. Run: php artisan serve --host=0.0.0.0 --port=8000║\n";
echo "║   3. Access from phone: http://[YOUR_IP]:8000          ║\n";
echo "║   4. Login with: admin / admin123                      ║\n";
echo "╚════════════════════════════════════════════════════════╝\n";
?>
