#!/usr/bin/env php
<?php
/**
 * MediaLAN Quick Server
 * Lightweight PHP server runner (no Laravel framework required)
 * 
 * Usage: php run_server.php [port]
 */

define('BASE_PATH', __DIR__);
define('PORT', isset($argv[1]) ? (int)$argv[1] : 8000);
define('HOST', '0.0.0.0');

echo "\n╔═══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                        MediaLAN - Quick Server                                ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "Starting lightweight server...\n";
echo "Host: " . HOST . "\n";
echo "Port: " . PORT . "\n";
echo "Root: " . BASE_PATH . "/public\n\n";

// Check if Laravel framework is available
if (is_dir(BASE_PATH . '/vendor/laravel/framework')) {
    echo "✓ Full Laravel framework detected\n";
    echo "→ Running full application\n\n";
    
    require BASE_PATH . '/vendor/autoload.php';
    $app = require BASE_PATH . '/bootstrap/app.php';
    $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
    $response = $kernel->handle(
        $request = \Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
} else {
    echo "⚠ Laravel framework not yet fully installed\n";
    echo "→ Running lightweight fallback server\n\n";
    
    $cmd = sprintf(
        '"C:\\xampp\\php\\php.exe" -S %s:%d -t "%s/public" -r "%s/router.php" 2>&1',
        HOST,
        PORT,
        BASE_PATH,
        BASE_PATH
    );
    
    echo "Command: $cmd\n\n";
    echo "MediaLAN is running!\n\n";
    echo "Open in browser:\n";
    echo "  Desktop: http://localhost:" . PORT . "/fallback.php\n";
    echo "  Phone:   http://[YOUR_IP]:" . PORT . "/fallback.php\n\n";
    echo "Press Ctrl+C to stop\n\n";
    
    passthru($cmd);
}
?>
