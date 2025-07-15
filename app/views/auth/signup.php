<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../config.php'; // Updated config path
require_once __DIR__ . '/../../functions/auth.php';
if (is_logged_in()) {
  header('Location: /');
  exit();
}
// Page Configuration
$pageTitle = 'Sign Up';
$pageCSS = '';

// Start output buffering
ob_start();

// Initialize variables
$errors = [];
$success = false;
$username = $email = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verify CSRF token
  if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $errors[] = "Invalid CSRF token.";
  } else {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($username) || !preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
      $errors[] = "Username must be 4-20 characters (letters, numbers, underscores).";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 8) {
      $errors[] = "Password must be at least 8 characters long.";
    } elseif (!password_strength_check($password)) {
      $errors[] = "Password must include uppercase, lowercase, number, and special character.";
    } elseif ($password !== $confirm_password) {
      $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
      try {
        // Check if username/email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->rowCount() > 0) {
          $errors[] = "Username or email already exists.";
        } else {
          // Create user account with default role
          $hashed_password = password_hash($password, PASSWORD_BCRYPT);
          $verification_token = generate_secure_token();

          $stmt = $pdo->prepare("
                        INSERT INTO users 
                        (username, email, password, role, verification_token, created_at) 
                        VALUES (?, ?, ?, ?, ?, NOW())
                    ");
          $stmt->execute([
            $username,
            $email,
            $hashed_password,
            ROLE_BASIC, // Using constant from roles.php
            $verification_token
          ]);

          // Send verification email (pseudo-code)
          // send_verification_email($email, $verification_token);

          $success = true;
          $_SESSION['signup_success'] = true;
          header("Location: /login");
          exit();
        }
      } catch (PDOException $e) {
        error_log("Signup error: " . $e->getMessage());
        $errors[] = "A system error occurred. Please try again later.";
      }
    }
  }
}
?>

<!-- Content Start -->
<div class="container">
  <?php if (!empty($errors)): ?>
    <div class="error-message">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success-message">
      Registration successful! Please check your email to verify your account.
    </div>
  <?php else: ?>
    <div class="card">
      <div class="card-image">
        <h2 class="card-heading">
          Get started
          <small>Let us create your account</small>
        </h2>
      </div>
      <form class="card-form" method="POST">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

        <div class="input">
          <input type="text" class="input-field" name="username" value="<?= htmlspecialchars($username) ?>" required />
          <label class="input-label">Username (4-20 characters)</label>
        </div>

        <div class="input">
          <input type="email" class="input-field" name="email" value="<?= htmlspecialchars($email) ?>" required />
          <label class="input-label">Email</label>
        </div>

        <div class="input">
          <input type="password" class="input-field" name="password" required />
          <label class="input-label">Password</label>
        </div>

        <div class="input">
          <input type="password" class="input-field" name="confirm_password" required />
          <label class="input-label">Confirm Password</label>
        </div>

        <div class="action">
          <button type="submit" class="action-button">Get started</button>
        </div>
      </form>
      <div class="card-info">
        <p>By signing up you are agreeing to our <a href="#">Terms and Conditions</a></p>
      </div>
    </div>
  <?php endif; ?>
</div>

<style>
  img {
    max-width: 100%;
    display: block;
  }

  input {
    appearance: none;
    border-radius: 0;
  }

  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100%;
    padding: 20px;
  }

  .error-message {
    background-color: #fdecea;
    color: #d32f2f;
    padding: 14px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
    max-width: 500px;
    width: 100%;
  }

  .success-message {
    background-color: #e8f5e9;
    color: #2e7d32;
    padding: 14px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
    max-width: 425px;
    width: 100%;
  }

  .card {
    margin: 1rem auto;
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 500px;
    background-color: var(--tertiary);
    border-radius: 10px;
    padding: .75rem;
  }

  .card-image {
    border-radius: 8px;
    overflow: hidden;
    background-color: #eddbc5;
    padding-bottom: 40%;
    background-image: url('https://assets.codepen.io/285131/coffee_1.jpg');
    background-repeat: no-repeat;
    background-size: 100%;
    background-position: 190px 10%;
    position: relative;
  }

  .card-heading {
    position: absolute;
    left: 10%;
    top: 15%;
    right: 10%;
    font-size: 1.75rem;
    font-weight: 700;
    color: #735400;
    line-height: 1.222;
  }

  .card-heading small {
    display: block;
    font-size: .75em;
    font-weight: 400;
    margin-top: .25em;
  }

  .card-form {
    padding: 2rem 1rem 0;
  }

  .input {
    display: flex;
    flex-direction: column-reverse;
    position: relative;
    padding-top: 1rem;
  }

  .input+.input {
    margin-top: 1rem;
  }

  .input-label {
    color: var(--primaryFont);
    position: absolute;
    top: 1rem;
    transition: .25s ease;
  }

  .input-field {
    border: 0;
    z-index: 1;
    background-color: transparent;
    border-bottom: 2px solid #eee;
    font: inherit;
    font-size: 1rem;
    padding: .25rem 0;
  }

  .input-field:focus,
  .input-field:valid {
    outline: 0;
    border-bottom-color: var(--tertiaryFont);
  }

  .input-field:focus+.input-label,
  .input-field:valid+.input-label {
    color: var(--tertiaryFont);
    transform: translateY(-1.5rem);
  }

  .action {
    margin-top: 2rem;
  }

  .action-button {
    font: inherit;
    font-size: 1rem;
    padding: 1em;
    width: 100%;
    font-weight: 500;
    background-color: var(--tertiaryFont);
    border-radius: 6px;
    color: var(--primaryFont);
    border: 0;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .action-button:focus {
    outline: 0;
  }

  .action-button:hover {
    background-color: var(--tertiaryFont);
  }

  .card-info {
    padding: 1rem 1rem;
    text-align: center;
    font-size: .875rem;
    color: var(--secondaryFont);
  }

  .card-info a {
    display: block;
    color: var(--tertiaryFont);
    text-decoration: none;
  }

  .card-info a:hover {
    text-decoration: underline;
  }
</style>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the base template
include __DIR__ . '/../layouts/layout.php';