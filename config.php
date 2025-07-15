<?php
// Absolute path to project root
define('ROOT_PATH', __DIR__);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', ROOT_PATH . '/logs/php_errors.log');

// Create required directories
$requiredDirs = ['/logs', '/uploads', '/cache'];
foreach ($requiredDirs as $dir) {
  if (!file_exists(ROOT_PATH . $dir)) {
    mkdir(ROOT_PATH . $dir, 0755, true);
  }
}

// Load configurations
require_once ROOT_PATH . '/app/config/database.php';
require_once ROOT_PATH . '/app/config/security.php';
require_once ROOT_PATH . '/app/config/roles.php';

// Load functions
require_once ROOT_PATH . '/app/functions/auth.php';
require_once ROOT_PATH . '/app/functions/security.php';
require_once ROOT_PATH . '/app/functions/helpers.php';

// Start secure session
if (session_status() === PHP_SESSION_NONE) {
  session_start();

  if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
  } elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
  }
}

// Initialize CSRF token
if (empty($_SESSION['csrf_token']) || time() > $_SESSION['csrf_token_expiry']) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  $_SESSION['csrf_token_expiry'] = time() + CSRF_TOKEN_EXPIRY;
}

// Auto-load classes
spl_autoload_register(function ($class) {
  $file = ROOT_PATH . '/app/classes/' . str_replace('\\', '/', $class) . '.php';
  if (file_exists($file)) {
    require $file;
  }
});

// Set default timezone
date_default_timezone_set('UTC');