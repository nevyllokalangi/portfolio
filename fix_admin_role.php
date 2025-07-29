<?php
require_once __DIR__ . '/config.php';

echo "<h1>Admin Role Fix Script</h1>";

try {
  // Check current users and their roles
  echo "<h2>Current Users:</h2>";
  $stmt = $pdo->query("SELECT id, username, email, role FROM users");
  $users = $stmt->fetchAll();

  echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
  echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>";

  foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
    echo "<td>" . htmlspecialchars($user['role']) . "</td>";
    echo "<td>";
    if ($user['role'] !== 'admin') {
      echo "<a href='?action=make_admin&id=" . $user['id'] . "'>Make Admin</a>";
    } else {
      echo "Already Admin";
    }
    echo "</td>";
    echo "</tr>";
  }
  echo "</table>";

  // Handle make admin action
  if (isset($_GET['action']) && $_GET['action'] === 'make_admin' && isset($_GET['id'])) {
    $userId = (int) $_GET['id'];

    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $stmt->execute([$userId]);

    echo "<h2>User ID $userId has been made admin!</h2>";
    echo "<p><a href='fix_admin_role.php'>Refresh to see changes</a></p>";
  }

  // Show role constants
  echo "<h2>Role Constants:</h2>";
  echo "<p>ROLE_BASIC: " . ROLE_BASIC . "</p>";
  echo "<p>ROLE_EDITOR: " . ROLE_EDITOR . "</p>";
  echo "<p>ROLE_ADMIN: " . ROLE_ADMIN . "</p>";

  echo "<h2>Role Hierarchy:</h2>";
  echo "<pre>" . print_r($role_hierarchy, true) . "</pre>";

} catch (Exception $e) {
  echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<style>
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }

  table {
    margin: 20px 0;
  }

  th,
  td {
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }

  a {
    color: blue;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }
</style>