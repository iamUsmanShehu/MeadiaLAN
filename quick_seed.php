<?php
/**
 * MediaLAN - Simple Seed
 */

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║      MediaLAN - Initial Data Setup                        ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('USE medialan');
    echo "[✓] Connected to database\n\n";
} catch (Exception $e) {
    die("[✗] Failed: " . $e->getMessage() . "\n");
}

// Create admin user
echo "[1] Creating admin user...\n";

try {
    // Check if admin already exists
    $existing = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $existing->execute(['admin@medialn.local']);
    $admin = $existing->fetch();
    
    if ($admin) {
        echo "    → Admin user already exists\n";
    } else {
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $result = $stmt->execute([
            'Administrator',
            'admin@medialn.local',
            $password
        ]);
        
        if ($result) {
            echo "    ✓ Admin created\n";
            echo "      Email:    admin@medialn.local\n";
            echo "      Password: admin123\n";
        } else {
            echo "    ✗ Insert failed\n";
        }
    }
} catch (Exception $e) {
    echo "    ✗ Error: " . $e->getMessage() . "\n";
}

// Verify categories are seeded
echo "\n[2] Verifying categories...\n";

try {
    $count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    echo "    ✓ Found $count categories\n";
} catch (Exception $e) {
    echo "    ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n╔═══════════════════════════════════════════════════════════╗\n";
echo "║ ✓ Setup Complete!                                         ║\n";
echo "╠═══════════════════════════════════════════════════════════╣\n";
echo "║ Database: medialan                                        ║\n";
echo "║ Tables: Ready                                             ║\n";
echo "║ Admin Login:                                              ║\n";
echo "║   Email:    admin@medialn.local                           ║\n";
echo "║   Password: admin123                                      ║\n";
echo "║                                                           ║\n";
echo "║ IMPORTANT: Change password after first login!             ║\n";
echo "║                                                           ║\n";
echo "║ Next: Wait for Composer, then run:                        ║\n";
echo "║   php artisan serve --host=0.0.0.0 --port=8000           ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
?>
