<?php
/**
 * MediaLAN - Router for PHP Development Server
 * Handles routing without Laravel framework
 */

// Get the requested path
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = str_replace('/MediaLAN', '', $requestUri);

// Static files (CSS, JS, images)
if (preg_match('/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$/i', $requestUri)) {
    return false; // Let PHP server handle static files
}

// Home page
if ($requestUri === '/' || $requestUri === '/index.php') {
    include __DIR__ . '/public/fallback.php';
    return true;
}

// Fallback interface
if ($requestUri === '/fallback.php') {
    include __DIR__ . '/public/fallback.php';
    return true;
}

// Status page
if ($requestUri === '/status.html' || $requestUri === '/status') {
    include __DIR__ . '/public/status.html';
    return true;
}

// API endpoints
if (strpos($requestUri, '/api/') === 0) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'offline',
        'message' => 'Laravel framework still installing via Composer',
        'use_fallback' => 'Access http://localhost:8000/fallback.php instead'
    ]);
    return true;
}

// Not found
http_response_code(404);
echo "<!DOCTYPE html>
<html>
<head><title>404</title><style>body{font-family:sans-serif;background:#1a1a1a;color:#fff;padding:40px;text-align:center}</style></head>
<body>
<h1>404 - Not Found</h1>
<p><a href='/'>← Back to Home</a></p>
</body>
</html>";
return true;
?>
