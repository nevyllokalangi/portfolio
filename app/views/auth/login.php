<?php
// Initialize settings and includes
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../config.php';

// Session handling
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Page configuration
$pageTitle = 'Login';
$pageCSS = '';

// Redirect if already logged in
if (is_logged_in()) {
  redirect_after_login();
}

// Process login form
$errors = [];
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors = handle_login_submission();
  $username = $_POST['username'] ?? '';
}

// Start output buffering
ob_start();
?>
<!-- Content Start -->
<div class="login-page">
  <div class="login-card">
    <div class="login-header">
      <h1>Welcome Back</h1>
      <p>Please sign in to continue</p>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="login-error">
        <?php foreach ($errors as $error): ?>
          <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="login-form">
      <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

      <div class="form-group">
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>"
          placeholder="Username or Email">
      </div>

      <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Password">
      </div>

      <div class="form-options">
        <label class="remember-me">
          <input type="checkbox" id="remember" name="remember">
          <span>Remember me</span>
        </label>
      </div>

      <button type="submit" class="login-button">Sign In</button>
    </form>
  </div>
</div>

<style>
  .login-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    background-color: var(--primary);
  }

  .login-card {
    width: 100%;
    max-width: 400px;
    background: var(--secondary);
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 40px;
    border: 1px solid var(--borderColor);
  }

  .login-header {
    text-align: center;
    margin-bottom: 30px;
  }

  .login-header h1 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--tertiaryFont);
  }

  .login-header p {
    color: var(--secondaryFont);
    font-size: 14px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--borderColor);
    border-radius: 4px;
    font-size: 14px;
    transition: var(--transition);
    background-color: var(--tertiary);
    color: var(--primaryFont);
  }

  .form-group input:focus {
    border-color: var(--accentBlue);
    outline: none;
  }

  .remember-me {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: var(--secondaryFont);
    margin-bottom: 20px;
  }

  .remember-me input {
    margin-right: 8px;
  }

  .login-button {
    width: 100%;
    padding: 12px;
    background-color: var(--tertiaryFont);
    color: var(--primaryFont);
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
  }

  .login-button:hover {
    background-color: rgb(149, 21, 48);
  }

  .login-error {
    background-color: #ffebee;
    color: #d32f2f;
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-size: 14px;
  }

  .login-error p {
    margin: 0;
    padding: 0;
  }
</style>

<?php
// Finalize template
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';