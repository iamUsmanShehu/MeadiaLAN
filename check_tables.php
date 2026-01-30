<?php
$pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
$pdo->exec('USE medialan');

echo "Users table structure:\n";
$cols = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $col) {
    echo "  - {$col['Field']}: {$col['Type']} " . ($col['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
}

echo "\nAdmins table structure:\n";
$cols = $pdo->query("DESCRIBE admins")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $col) {
    echo "  - {$col['Field']}: {$col['Type']} " . ($col['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
}

echo "\nAll tables:\n";
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) echo "  - $t\n";
?>
