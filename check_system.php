#!/usr/bin/env php
<?php
/**
 * MediaLAN - System Check
 * Verifies everything is ready to run
 */

echo "\n╔═══════════════════════════════════════════════════════════╗\n";
echo "║         MediaLAN - System Status Check                    ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

$issues = [];
$warnings = [];
$success = [];

// PHP Version
echo "[1] PHP Version: " . PHP_VERSION . " ... ";
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "✅ OK\n";
    $success[] = 'PHP Version';
} else {
    echo "❌ FAILED\n";
    $issues[] = 'PHP 7.4+ required';
}

// Database Connection
echo "[2] MySQL Database: ";
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    echo "✅ OK\n";
    $success[] = 'Database Connection';
    
    // Check medialan database
    echo "[3] Database 'medialan': ";
    try {
        $pdo->exec('USE medialan');
        echo "✅ OK\n";
        $success[] = 'MediaLAN Database';
        
        // Check tables
        echo "[4] Database Tables: ";
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (count($tables) >= 5) {
            echo "✅ OK (" . count($tables) . " tables)\n";
            $success[] = 'Database Tables';
            
            // Check admin user
            echo "[5] Admin User: ";
            $count = $pdo->query("SELECT COUNT(*) FROM users WHERE email LIKE '%admin%'")->fetchColumn();
            if ($count > 0) {
                echo "✅ OK\n";
                $success[] = 'Admin User';
            } else {
                echo "⚠️  Missing\n";
                $warnings[] = 'Admin user not found - run: php quick_seed.php';
            }
            
            // Check categories
            echo "[6] Categories: ";
            $count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
            echo "✅ OK (" . $count . " categories)\n";
            $success[] = 'Categories';
        } else {
            echo "❌ FAILED (" . count($tables) . " found, need 5+)\n";
            $issues[] = 'Missing database tables - run: php setup_database.php';
        }
    } catch (Exception $e) {
        echo "❌ FAILED\n";
        $issues[] = 'Database not found - run: php setup_database.php';
    }
} catch (Exception $e) {
    echo "❌ FAILED\n";
    echo "    Error: " . $e->getMessage() . "\n";
    $issues[] = 'MySQL connection failed - check XAMPP is running';
}

// Check critical files
echo "[7] Critical Files: ";
$files = [
    '.env' => '.env configuration',
    'public/index.php' => 'Laravel entry point',
    'public/fallback.php' => 'Fallback lightweight interface',
    'app/Models' => 'Models directory',
];
$missing = [];
foreach ($files as $file => $name) {
    if (!file_exists(__DIR__ . '/' . $file)) {
        $missing[] = $name;
    }
}
if (empty($missing)) {
    echo "✅ OK\n";
    $success[] = 'Critical Files';
} else {
    echo "⚠️  Missing: " . implode(', ', $missing) . "\n";
    $warnings[] = 'Some files missing';
}

// Composer
echo "[8] Composer Vendor: ";
if (is_dir(__DIR__ . '/vendor') && file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✅ OK\n";
    $success[] = 'Composer Vendor';
} else {
    echo "⏳ Still downloading\n";
    $warnings[] = 'Composer dependencies not fully installed yet';
}

// Storage folder
echo "[9] Storage Folder: ";
if (is_dir(__DIR__ . '/storage') && is_writable(__DIR__ . '/storage')) {
    echo "✅ OK\n";
    $success[] = 'Storage Folder';
} else {
    echo "⚠️  Check permissions\n";
    $warnings[] = 'Storage folder may not be writable';
}

// Summary
echo "\n╔═══════════════════════════════════════════════════════════╗\n";
echo "║ Summary                                                   ║\n";
echo "╠═══════════════════════════════════════════════════════════╣\n";

if (empty($issues)) {
    echo "║ ✅ All critical checks passed!                           ║\n";
} else {
    echo "║ ❌ Issues found:                                          ║\n";
    foreach ($issues as $issue) {
        echo "║    • $issue\n";
    }
}

if (!empty($warnings)) {
    echo "║\n║ ⚠️  Warnings:                                             ║\n";
    foreach ($warnings as $warning) {
        echo "║    • $warning\n";
    }
}

echo "║\n║ Status:                                                   ║\n";
echo "║    ✅ Successful: " . count($success) . "\n";
if (!empty($warnings)) {
    echo "║    ⚠️  Warnings: " . count($warnings) . "\n";
}
if (!empty($issues)) {
    echo "║    ❌ Issues: " . count($issues) . "\n";
}

echo "╠═══════════════════════════════════════════════════════════╣\n";
echo "║ Next Steps:                                               ║\n";
echo "║                                                           ║\n";

if (empty($issues)) {
    echo "║  1. Access: http://localhost/MediaLAN/public/fallback.php║\n";
    echo "║  2. From phone: http://[YOUR_IP]/MediaLAN/public/fallback.php\n";
    echo "║  3. Wait for Composer to finish                         ║\n";
    echo "║  4. Run: php artisan serve --host=0.0.0.0 --port=8000   ║\n";
} else {
    echo "║  Please fix the issues above before proceeding           ║\n";
}

echo "║                                                           ║\n";
echo "║ Login: admin@medialn.local / admin123                     ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

exit(empty($issues) ? 0 : 1);
?>
