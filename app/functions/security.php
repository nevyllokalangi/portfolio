<?php
function generate_csrf_token()
{
  if (empty($_SESSION['csrf_token']) || time() > $_SESSION['csrf_token_expiry']) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_expiry'] = time() + CSRF_TOKEN_EXPIRY;
  }
  return $_SESSION['csrf_token'];
}

function verify_csrf_token($token)
{
  return isset($_SESSION['csrf_token'], $_SESSION['csrf_token_expiry']) &&
    hash_equals($_SESSION['csrf_token'], $token) &&
    time() <= $_SESSION['csrf_token_expiry'];
}

function generate_secure_token($length = 32)
{
  try {
    return bin2hex(random_bytes($length));
  } catch (Exception $e) {
    error_log("Token generation failed: " . $e->getMessage());
    return md5(uniqid(mt_rand(), true));
  }
}