<?php
$pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
$pdo->exec('USE medialan');
$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
echo "Tables created:\n";
foreach ($tables as $t) echo "  - $t\n";
echo "\nColumns in 'categories':\n";
$cols = $pdo->query('DESCRIBE categories')->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) echo "  - {$c['Field']}: {$c['Type']}\n";
?>
