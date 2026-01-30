<?php
/**
 * MediaLAN Lightweight Router
 * Works without full Laravel installation
 */

// PHP 7.4+ Check
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('MediaLAN requires PHP 7.4+');
}

define('BASE_PATH', dirname(dirname(__FILE__)));
define('DB_PATH', BASE_PATH . '/database');

// Simple autoloader
spl_autoload_register(function ($class) {
    if (strpos($class, 'App\\') === 0) {
        $path = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
});

// Load Composer autoload if available
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

// Simple database class for basic operations
class SimpleDB {
    private $dbPath;
    private $jsonFile;

    public function __construct() {
        $this->dbPath = DB_PATH;
        $this->jsonFile = $this->dbPath . '/data.json';
        $this->ensureDataFile();
    }

    private function ensureDataFile() {
        if (!file_exists($this->jsonFile)) {
            $data = [
                'categories' => [
                    ['id' => 1, 'name' => 'Movies', 'description' => 'Movies and Films'],
                    ['id' => 2, 'name' => 'TV Series', 'description' => 'TV Shows and Series'],
                    ['id' => 3, 'name' => 'Documentaries', 'description' => 'Documentary Content'],
                    ['id' => 4, 'name' => 'Education', 'description' => 'Educational Content'],
                    ['id' => 5, 'name' => 'Music', 'description' => 'Music Videos and Albums'],
                    ['id' => 6, 'name' => 'Sports', 'description' => 'Sports Events'],
                    ['id' => 7, 'name' => 'Podcasts', 'description' => 'Podcast Episodes'],
                    ['id' => 8, 'name' => 'Other', 'description' => 'Other Content'],
                ],
                'media' => [],
                'pins' => [],
                'downloads_log' => [],
                'admin_users' => [
                    [
                        'id' => 1,
                        'name' => 'Administrator',
                        'username' => 'admin',
                        'password' => password_hash('admin123', PASSWORD_BCRYPT),
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                ]
            ];
            @mkdir(dirname($this->jsonFile), 0755, true);
            file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
        }
    }

    public function getData() {
        return json_decode(file_get_contents($this->jsonFile), true);
    }

    public function saveData($data) {
        file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getCategories() {
        return $this->getData()['categories'] ?? [];
    }
}

// Router
class Router {
    private $db;

    public function __construct() {
        $this->db = new SimpleDB();
        $this->route();
    }

    private function route() {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = str_replace('/MediaLAN', '', $path);
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Remove trailing slash
        $path = rtrim($path, '/') ?: '/';

        // Route matching
        switch ($path) {
            case '/':
            case '/home':
                $this->renderHome();
                break;
            case '/categories':
                $this->renderCategories();
                break;
            case '/search':
                $this->renderSearch();
                break;
            case '/admin/login':
                if ($method === 'POST') {
                    $this->handleAdminLogin();
                } else {
                    $this->renderAdminLogin();
                }
                break;
            case '/admin/dashboard':
                $this->renderAdminDashboard();
                break;
            case '/api/status':
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'online',
                    'php_version' => PHP_VERSION,
                    'laravel' => file_exists(BASE_PATH . '/vendor/autoload.php') ? 'installed' : 'installing',
                    'database' => 'json',
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                break;
            default:
                $this->render404();
        }
    }

    private function renderHome() {
        $categories = $this->db->getCategories();
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaLAN - Local Media Server</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #1a1a1a; color: #fff; }
        .navbar {
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #3b82f6;
        }
        .navbar h1 { font-size: 1.5em; color: #3b82f6; }
        .navbar a { color: #fff; margin-left: 20px; text-decoration: none; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        h2 { margin: 30px 0 20px; color: #3b82f6; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .card {
            background: #2d2d2d;
            padding: 20px;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #444;
            transition: all 0.3s;
        }
        .card:hover { border-color: #3b82f6; background: #333; transform: translateY(-2px); }
        .card h3 { color: #3b82f6; margin-bottom: 10px; }
        .card p { color: #aaa; font-size: 0.9em; }
        .status-banner {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .status-banner p { margin: 5px 0; }
        .status-item { color: #aaa; }
        .status-item.ready { color: #10b981; }
        .status-item.pending { color: #f59e0b; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🎬 MediaLAN</h1>
        <div>
            <a href="/">Home</a>
            <a href="/MediaLAN/public/index.php?page=admin">Admin</a>
            <a href="/MediaLAN/public/status.html">Status</a>
        </div>
    </div>

    <div class="container">
        <div class="status-banner">
            <h3>System Status</h3>
            <p class="status-item ready">✓ PHP 7.4.2 - Compatible</p>
            <p class="status-item <?php echo file_exists(BASE_PATH . '/vendor/autoload.php') ? 'ready' : 'pending'; ?>">
                <?php echo file_exists(BASE_PATH . '/vendor/autoload.php') ? '✓' : '⏳'; ?> Laravel Framework <?php echo file_exists(BASE_PATH . '/vendor/autoload.php') ? '- Installed' : '- Installing'; ?>
            </p>
            <p style="margin-top: 15px; font-size: 0.9em; color: #888;">
                This is a temporary lightweight interface while the full Laravel application installs.
                Access will be fully functional once installation completes.
            </p>
        </div>

        <h2>📁 Categories</h2>
        <div class="grid">
            <?php foreach ($categories as $category): ?>
            <div class="card" onclick="location.href='/MediaLAN/public/index.php?category=<?php echo urlencode($category['name']); ?>'">
                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                <p><?php echo htmlspecialchars($category['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <h2 style="margin-top: 50px;">🔧 Setup Instructions</h2>
        <div style="background: #2d2d2d; padding: 20px; border-radius: 8px;">
            <p style="margin-bottom: 10px;">Run the following commands to complete setup:</p>
            <pre style="background: #1a1a1a; padding: 15px; border-radius: 4px; overflow-x: auto;">cd c:\xampp\htdocs\MediaLAN
php artisan migrate
php artisan db:seed
php artisan serve --host=0.0.0.0 --port=8000</pre>
            <p style="margin-top: 20px; color: #aaa; font-size: 0.9em;">
                Once setup is complete, access MediaLAN from your phone at:<br>
                <strong style="color: #3b82f6;">http://[YOUR_COMPUTER_IP]:8000</strong>
            </p>
        </div>
    </div>
</body>
</html>
        <?php
    }

    private function renderCategories() {
        $categories = $this->db->getCategories();
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - MediaLAN</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #1a1a1a; color: #fff; }
        .navbar { background: #2d2d2d; padding: 15px 20px; display: flex; gap: 20px; align-items: center; border-bottom: 2px solid #3b82f6; }
        .navbar a { color: #fff; text-decoration: none; }
        .navbar a:hover { color: #3b82f6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        h2 { margin: 20px 0; color: #3b82f6; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
        .card { background: #2d2d2d; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #444; cursor: pointer; transition: all 0.3s; }
        .card:hover { border-color: #3b82f6; background: #333; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/">← Back to Home</a>
        <h2 style="margin: 0;">All Categories</h2>
    </div>
    <div class="container">
        <div class="grid">
            <?php foreach ($categories as $cat): ?>
            <div class="card">
                <h4><?php echo htmlspecialchars($cat['name']); ?></h4>
                <p style="font-size: 0.8em; color: #aaa;"><?php echo htmlspecialchars($cat['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
        <?php
    }

    private function renderSearch() {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - MediaLAN</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #1a1a1a; color: #fff; }
        .navbar { background: #2d2d2d; padding: 15px 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .search-form { margin-bottom: 30px; }
        input[type="search"] { width: 100%; padding: 15px; background: #2d2d2d; border: 1px solid #444; color: #fff; border-radius: 8px; font-size: 1em; }
        input[type="search"]:focus { outline: none; border-color: #3b82f6; }
        .message { background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6; padding: 15px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/" style="color: #fff; text-decoration: none;">← Back</a>
    </div>
    <div class="container">
        <h2 style="margin-bottom: 20px; color: #3b82f6;">🔍 Search MediaLAN</h2>
        <div class="search-form">
            <input type="search" placeholder="Search for media..." autofocus>
        </div>
        <div class="message">
            <p>Full search functionality available once Laravel installation completes.</p>
        </div>
    </div>
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
    <title>Admin Login - MediaLAN</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            padding: 20px;
        }
        .login-box {
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
        }
        h1 { font-size: 2em; color: #3b82f6; margin-bottom: 30px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input { width: 100%; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 8px; font-size: 1em; }
        input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        button { width: 100%; padding: 12px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        button:hover { background: #2563eb; }
        .info { background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); padding: 15px; border-radius: 8px; margin-top: 20px; font-size: 0.9em; }
        code { background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>🔐 MediaLAN Admin</h1>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="info">
            <strong>Default Credentials:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin123</code>
        </div>
    </div>
</body>
</html>
        <?php
    }

    private function handleAdminLogin() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $data = $this->db->getData();
        foreach ($data['admin_users'] as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                $_SESSION['admin'] = $user['id'];
                header('Location: /MediaLAN/public/index.php?page=dashboard');
                exit;
            }
        }
        header('Location: /MediaLAN/public/index.php?page=login&error=invalid');
        exit;
    }

    private function renderAdminDashboard() {
        $data = $this->db->getData();
        $categoryCount = count($data['categories']);
        $mediaCount = count($data['media']);
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MediaLAN</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #1a1a1a; color: #fff; }
        .navbar { background: #2d2d2d; padding: 15px 20px; display: flex; gap: 20px; }
        .navbar a { color: #fff; text-decoration: none; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #2d2d2d; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6; }
        .stat-value { font-size: 2em; color: #3b82f6; font-weight: bold; }
        .stat-label { color: #aaa; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/">← Home</a>
        <h2 style="margin: 0; color: #3b82f6;">Admin Dashboard</h2>
    </div>
    <div class="container">
        <h2 style="margin-bottom: 20px; color: #3b82f6;">📊 Statistics</h2>
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value"><?php echo $categoryCount; ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $mediaCount; ?></div>
                <div class="stat-label">Media Files</div>
            </div>
        </div>
        <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6; padding: 20px; border-radius: 8px;">
            <p style="margin-bottom: 15px;">✅ Admin dashboard is working with temporary JSON database.</p>
            <p>Full functionality with media uploads and advanced management available once Laravel is installed.</p>
        </div>
    </div>
</body>
</html>
        <?php
    }

    private function render404() {
        header('HTTP/1.1 404 Not Found');
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <style>
        body { font-family: sans-serif; background: #1a1a1a; color: #fff; padding: 40px; text-align: center; }
        h1 { color: #3b82f6; margin-bottom: 20px; }
        a { color: #3b82f6; text-decoration: none; }
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

// Start the lightweight application
new Router();
