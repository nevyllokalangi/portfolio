<?php
function is_logged_in()
{
  return !empty($_SESSION['user_id']) &&
    $_SESSION['logged_in'] === true &&
    (!isset($_SESSION['last_activity']) || (time() - $_SESSION['last_activity'] < SESSION_TIMEOUT));
}

function require_login()
{
  if (!is_logged_in()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: /login");
    exit();
  }
  $_SESSION['last_activity'] = time();
}

function require_role($required_role)
{
  require_login();
  global $role_hierarchy;

  // Debug information
  error_log("Debug - User ID: " . ($_SESSION['user_id'] ?? 'not set'));
  error_log("Debug - User Role: " . ($_SESSION['role'] ?? 'not set'));
  error_log("Debug - Required Role: " . $required_role);
  error_log("Debug - Role Hierarchy: " . print_r($role_hierarchy, true));

  if (empty($_SESSION['role'])) {
    header("HTTP/1.0 403 Forbidden");
    exit("Access denied: No role assigned. User role: " . ($_SESSION['role'] ?? 'not set'));
  }

  if (
    !isset($role_hierarchy[$_SESSION['role']]) ||
    $role_hierarchy[$_SESSION['role']] < $role_hierarchy[$required_role]
  ) {
    header("HTTP/1.0 403 Forbidden");
    exit("Access denied: Insufficient privileges. User role: " . $_SESSION['role'] . ", Required: " . $required_role);
  }
}

function password_strength_check($password)
{
  $strength = 0;
  $patterns = [
    '/[A-Z]/',
    '/[a-z]/',
    '/[0-9]/',
    '/[^A-Za-z0-9]/'
  ];

  foreach ($patterns as $pattern) {
    if (preg_match($pattern, $password)) {
      $strength++;
    }
  }

  $strength += min(floor(strlen($password) / 4), 3);
  return $strength >= 5;
}

function has_too_many_login_attempts($pdo, $ip)
{
  // Use session-based tracking instead of database
  $attempts = $_SESSION['login_attempts'][$ip] ?? 0;
  $lastAttempt = $_SESSION['login_attempts_time'][$ip] ?? 0;

  // Reset attempts if timeframe has passed
  if (time() - $lastAttempt > LOGIN_ATTEMPTS_TIMEFRAME) {
    $_SESSION['login_attempts'][$ip] = 0;
    return false;
  }

  return $attempts >= LOGIN_ATTEMPTS_LIMIT;
}

function get_user_by_credentials($pdo, $username)
{
  $stmt = $pdo->prepare("SELECT id, username, email, password, role, is_active FROM users WHERE username = ? OR email = ? LIMIT 1");
  $stmt->execute([$username, $username]);
  return $stmt->fetch();
}

function record_failed_attempt($pdo, $ip, $username)
{
  // Use session-based tracking instead of database
  if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
    $_SESSION['login_attempts_time'] = [];
  }

  $_SESSION['login_attempts'][$ip] = ($_SESSION['login_attempts'][$ip] ?? 0) + 1;
  $_SESSION['login_attempts_time'][$ip] = time();
}

function complete_successful_login($user, $remember, $pdo, $ip)
{
  session_regenerate_id(true);

  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['email'] = $user['email'];
  $_SESSION['role'] = $user['role'];
  $_SESSION['logged_in'] = true;
  $_SESSION['last_activity'] = time();

  // Debug information
  error_log("Login Debug - User ID: " . $user['id']);
  error_log("Login Debug - Username: " . $user['username']);
  error_log("Login Debug - Role: " . $user['role']);
  error_log("Login Debug - Session Role: " . $_SESSION['role']);

  if ($remember) {
    set_remember_me_cookie($user['id'], $pdo);
  }

  clear_login_attempts($pdo, $ip);
}

function clear_login_attempts($pdo, $ip)
{
  // Use session-based tracking instead of database
  if (isset($_SESSION['login_attempts'][$ip])) {
    unset($_SESSION['login_attempts'][$ip]);
    unset($_SESSION['login_attempts_time'][$ip]);
  }
}

function set_remember_me_cookie($user_id, $pdo)
{
  // Simplified remember me without database storage
  $token = bin2hex(random_bytes(32));
  $expires = time() + REMEMBER_ME_EXPIRE;

  setcookie(
    'remember_me',
    $user_id . ':' . $token,
    [
      'expires' => $expires,
      'path' => '/',
      'domain' => $_SERVER['HTTP_HOST'],
      'secure' => false, // Set to true in production with HTTPS
      'httponly' => true,
      'samesite' => 'Strict'
    ]
  );
}

function redirect_after_login()
{
  header("Location: " . ($_SESSION['redirect_url'] ?? '/admin/'));
  unset($_SESSION['redirect_url']);
  exit();
}

function handle_login_submission()
{
  global $pdo;
  $errors = [];

  if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $errors[] = "Invalid CSRF token.";
    return $errors;
  }

  // Validate inputs
  if (empty($_POST['username'])) {
    $errors[] = "Username or email is required.";
  }
  if (empty($_POST['password'])) {
    $errors[] = "Password is required.";
  }
  if (!empty($errors)) {
    return $errors;
  }

  try {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    $ip = $_SERVER['REMOTE_ADDR'];

    if (has_too_many_login_attempts($pdo, $ip)) {
      $errors[] = "Too many login attempts. Please try again later.";
      return $errors;
    }

    $user = get_user_by_credentials($pdo, $username);

    if (!$user || !password_verify($password, $user['password'])) {
      record_failed_attempt($pdo, $ip, $username);
      $errors[] = "Invalid username or password.";
      return $errors;
    }

    if (!$user['is_active']) {
      $errors[] = "Account is inactive. Please contact support.";
      return $errors;
    }

    complete_successful_login($user, $remember, $pdo, $ip);
    redirect_after_login();

  } catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    $errors[] = "A system error occurred. Please try again later.";
    return $errors;
  }
}