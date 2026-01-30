<?php
/**
 * MediaLAN - Minimalist Bootstrap
 * For when full Laravel installation is pending
 */

// ===== PHP VERSION CHECK =====
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('MediaLAN requires PHP 7.4.0 or higher. You are running ' . PHP_VERSION);
}

// ===== ENVIRONMENT SETUP =====
define('BASE_PATH', __DIR__);
define('PUBLIC_PATH', BASE_PATH . '/public');
define('APP_PATH', BASE_PATH . '/app');

// ===== COMPOSER AUTOLOAD =====
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
}

// ===== SIMPLE ROUTER FOR BASIC PAGES =====
class MediaLANMinimal {
    private $routes = [];

    public function __construct() {
        // Capture current request
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = str_replace('/MediaLAN', '', $path); // Remove base path if needed
        
        $this->route('GET', '/', 'home');
        $this->route('GET', '/admin/login', 'admin_login');
        $this->route('GET', '/status', 'status');
        
        $this->dispatch($path);
    }

    public function route($method, $path, $view) {
        $this->routes[$method . ':' . $path] = $view;
    }

    public function dispatch($path) {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $key = $method . ':' . $path;

        if (isset($this->routes[$key])) {
            $view = $this->routes[$key];
            $this->render($view);
        } else {
            $this->notFound();
        }
    }

    public function render($view) {
        switch ($view) {
            case 'home':
                $this->renderHome();
                break;
            case 'admin_login':
                $this->renderAdminLogin();
                break;
            case 'status':
                $this->renderStatus();
                break;
            default:
                $this->notFound();
        }
    }

    private function renderHome() {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaLAN - Initializing</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #3b82f6;
        }
        .status {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }
        .status-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
        }
        .status-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .status-ok { background: #10b981; }
        .status-pending { background: #f59e0b; }
        .status-error { background: #ef4444; }
        .progress {
            width: 100%;
            height: 8px;
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 4px;
            animation: progress 2s ease-in-out infinite;
        }
        @keyframes progress {
            0%, 100% { width: 30%; }
            50% { width: 70%; }
        }
        .message {
            font-size: 1.1em;
            line-height: 1.6;
            margin: 20px 0;
            color: #d1d5db;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .btn {
            flex: 1;
            min-width: 140px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .btn-secondary:hover { background: rgba(59, 130, 246, 0.3); }
        code {
            background: rgba(0,0,0,0.3);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎬 MediaLAN</h1>
        <p class="message">Local Media Server</p>

        <div class="status">
            <div class="status-item">
                <div class="status-icon status-ok">✓</div>
                <div>
                    <strong>PHP Version</strong><br>
                    <code><?php echo PHP_VERSION; ?></code> (Required: 7.4+)
                </div>
            </div>
            <div class="status-item">
                <div class="status-icon status-<?php echo file_exists(BASE_PATH . '/vendor/autoload.php') ? 'ok' : 'pending'; ?>">
                    <?php echo file_exists(BASE_PATH . '/vendor/autoload.php') ? '✓' : '⏳'; ?>
                </div>
                <div>
                    <strong>Composer Dependencies</strong><br>
                    <span id="vendor-status">
                        <?php 
                        if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
                            echo 'Loaded';
                        } else {
                            echo 'Installing...';
                        }
                        ?>
                    </span>
                </div>
            </div>
            <div class="status-item">
                <div class="status-icon status-<?php echo file_exists(BASE_PATH . '/.env') ? 'ok' : 'pending'; ?>">
                    <?php echo file_exists(BASE_PATH . '/.env') ? '✓' : '⏳'; ?>
                </div>
                <div>
                    <strong>Configuration</strong><br>
                    <?php echo file_exists(BASE_PATH . '/.env') ? '.env file found' : 'Creating .env...'; ?>
                </div>
            </div>
            <div class="status-item">
                <div class="status-icon status-pending">⏳</div>
                <div>
                    <strong>Database</strong><br>
                    Awaiting migration commands
                </div>
            </div>
        </div>

        <div class="progress">
            <div class="progress-bar"></div>
        </div>

        <div class="message">
            <strong>Setup in Progress</strong><br>
            MediaLAN is initializing. Dependencies are being installed via Composer.
            <br><br>
            This usually takes 1-5 minutes depending on your connection.
        </div>

        <div class="actions">
            <button class="btn btn-primary" onclick="location.reload()">Check Status</button>
            <a href="/MediaLAN/status" class="btn btn-secondary" style="text-decoration: none;">View Details</a>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.9em; color: #9ca3af;">
            <p><strong>Next Steps:</strong></p>
            <ol style="text-align: left; display: inline-block;">
                <li>Wait for Composer to finish installing dependencies</li>
                <li>Run: <code>php artisan migrate</code></li>
                <li>Run: <code>php artisan db:seed</code></li>
                <li>Access MediaLAN from your phone!</li>
            </ol>
        </div>
    </div>

    <script>
        // Auto-refresh every 5 seconds
        setTimeout(function() {
            location.reload();
        }, 5000);
    </script>
</body>
</html>
        <?php
    }

    private function renderAdminLogin() {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaLAN Admin - Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            max-width: 400px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 40px;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 30px;
            text-align: center;
            color: #3b82f6;
        }
        .message {
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.3);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #fed7aa;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #e5e7eb;
        }
        input {
            width: 100%;
            padding: 12px;
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
        }
        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #3b82f6;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        button:hover { background: #2563eb; }
        .info {
            margin-top: 20px;
            padding: 15px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            font-size: 0.9em;
            color: #bfdbfe;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>MediaLAN Admin</h1>
        <div class="message">
            ⏳ System is initializing. Please wait for the full installation to complete.
        </div>
        <div class="info">
            <strong>Default Credentials:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin123</code><br><br>
            <a href="/" style="color: #bfdbfe;">← Back to Home</a>
        </div>
    </div>
</body>
</html>
        <?php
    }

    private function renderStatus() {
        $composerLog = BASE_PATH . '/composer_install.log';
        $hasLog = file_exists($composerLog);
        $logContent = $hasLog ? file_get_contents($composerLog) : 'No log yet...';
        
        header('Content-Type: text/plain');
        echo "=== MediaLAN Installation Status ===\n\n";
        echo "PHP Version: " . PHP_VERSION . "\n";
        echo "Base Path: " . BASE_PATH . "\n";
        echo "Current Time: " . date('Y-m-d H:i:s') . "\n\n";
        
        echo "=== Vendor Status ===\n";
        echo "Vendor folder exists: " . (is_dir(BASE_PATH . '/vendor') ? 'Yes' : 'No') . "\n";
        echo "Autoload exists: " . (file_exists(BASE_PATH . '/vendor/autoload.php') ? 'Yes' : 'No') . "\n\n";
        
        echo "=== Composer Log ===\n";
        echo ($hasLog ? $logContent : 'No log file yet. Composer may still be running.');
    }

    private function notFound() {
        header('HTTP/1.1 404 Not Found');
        ?>
<!DOCTYPE html>
<html>
<head>
    <title>404 - Not Found</title>
    <style>
        body { font-family: sans-serif; background: #1e1e1e; color: #fff; padding: 20px; }
        h1 { color: #3b82f6; }
    </style>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <p><a href="/">← Back to Home</a></p>
</body>
</html>
        <?php
    }
}

// Run the minimal application
new MediaLANMinimal();
