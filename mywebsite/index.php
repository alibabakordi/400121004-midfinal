<?php
// Load configuration
require_once __DIR__.'/config/database.php';

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple autoloader function
function my_autoload($class) {
    $paths = [
        __DIR__.'/controllers/',
        __DIR__.'/models/'
    ];
    
    foreach ($paths as $path) {
        $file = $path.$class.'.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}

spl_autoload_register('my_autoload');

// Route handling
$page = $_GET['page'] ?? 'home';

try {
    switch ($page) {
        case 'login':
            $controller = new AuthController();
            $controller->login();
            break;
            
        case 'register':
            $controller = new AuthController();
            $controller->register();
            break;
            
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;
            
        case 'create_post':
            $controller = new DashboardController();
            $controller->createPost();
            break;
            
        case 'load_posts':
            $controller = new FeedController();
            $controller->loadMorePosts();
            break;
            
        case 'home':
        default:
            $controller = new HomeController();
            $controller->index();
            break;
    }
} catch (Error $e) {
    die("Error: ".$e->getMessage());
}