<?php
// Test PHP version
echo "PHP Version: " . PHP_VERSION . "\n";
echo "PHP Version ID: " . PHP_VERSION_ID . "\n";

// Test vendor check
$platform_check = __DIR__ . '/vendor/composer/platform_check.php';
if (file_exists($platform_check)) {
    echo "Platform check file exists\n";
    $content = file_get_contents($platform_check);
    if (strpos($content, '70400') !== false) {
        echo "✓ Platform check requires PHP 7.4+\n";
    } elseif (strpos($content, '80100') !== false) {
        echo "✗ Platform check requires PHP 8.1+ (NEEDS FIX)\n";
    } else {
        echo "? Could not determine platform check requirement\n";
    }
} else {
    echo "Platform check file does not exist yet\n";
}

// Test autoload
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    echo "Autoload file exists\n";
    try {
        require $autoload;
        echo "✓ Autoload loaded successfully\n";
    } catch (RuntimeException $e) {
        echo "✗ Autoload error: " . $e->getMessage() . "\n";
    }
} else {
    echo "Autoload file does not exist yet (composer still installing)\n";
}
?>