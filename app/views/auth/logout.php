<?php
require_once __DIR__ . '\..\..\..\config.php';

// Initialize session
session_start();

// Unset all session variables
$_SESSION = array();

// Delete remember me cookie if it exists
if (isset($_COOKIE['remember'])) {
  list($selector, $token) = explode(':', $_COOKIE['remember']);

  // Delete token from database
  try {
    $stmt = $pdo->prepare("DELETE FROM auth_tokens WHERE selector = ?");
    $stmt->execute([$selector]);
  } catch (PDOException $e) {
    error_log("Database error during logout: " . $e->getMessage());
  }

  // Clear cookie
  setcookie('remember', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], true, true);
}

// Destroy the session
session_destroy();

// Redirect to home page
header("Location: /home");
exit();
?>