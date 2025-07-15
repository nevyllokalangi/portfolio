<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Debug Info';
$pageCSS = '';

ob_start();
?>
<main class="content">
  <div class="seperator"></div>
  <div class="debug-container">
    <h1>Debug Information</h1>

    <h2>Session Information</h2>
    <pre><?php print_r($_SESSION); ?></pre>

    <h2>Role Information</h2>
    <p>ROLE_ADMIN constant: <?php echo defined('ROLE_ADMIN') ? ROLE_ADMIN : 'NOT DEFINED'; ?></p>
    <p>Role Hierarchy:</p>
    <pre><?php print_r($role_hierarchy); ?></pre>

    <h2>User Information</h2>
    <?php if (is_logged_in()): ?>
      <p>User ID: <?php echo $_SESSION['user_id']; ?></p>
      <p>Username: <?php echo $_SESSION['username']; ?></p>
      <p>Email: <?php echo $_SESSION['email']; ?></p>
      <p>Role: <?php echo $_SESSION['role']; ?></p>
      <p>Logged In: <?php echo $_SESSION['logged_in'] ? 'Yes' : 'No'; ?></p>

      <h3>Role Check Test</h3>
      <p>Can access admin?
        <?php echo (isset($role_hierarchy[$_SESSION['role']]) && $role_hierarchy[$_SESSION['role']] >= $role_hierarchy[ROLE_ADMIN]) ? 'Yes' : 'No'; ?>
      </p>
    <?php else: ?>
      <p>Not logged in</p>
    <?php endif; ?>

    <h2>Database Test</h2>
    <?php
    try {
      $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
      $stmt->execute([$_SESSION['user_id'] ?? 0]);
      $user = $stmt->fetch();
      if ($user) {
        echo "<p>Database user info:</p>";
        echo "<pre>" . print_r($user, true) . "</pre>";
      } else {
        echo "<p>User not found in database</p>";
      }
    } catch (Exception $e) {
      echo "<p>Database error: " . $e->getMessage() . "</p>";
    }
    ?>
  </div>
</main>

<style>
  .debug-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
  }

  .debug-container h1,
  .debug-container h2,
  .debug-container h3 {
    color: var(--primaryFont);
    margin-top: 2rem;
    margin-bottom: 1rem;
  }

  .debug-container pre {
    background: var(--secondary);
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    color: var(--primaryFont);
  }

  .debug-container p {
    color: var(--primaryFont);
    margin-bottom: 0.5rem;
  }
</style>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>