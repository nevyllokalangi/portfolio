<?php
function sanitize_input($data)
{
  if (is_array($data)) {
    return array_map('sanitize_input', $data);
  }
  return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function sanitize_output($data)
{
  return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function getPDO()
{
  global $pdo;
  return $pdo;
}

function redirect($url)
{
  header("Location: $url");
  exit();
}

function is_ajax_request()
{
  return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function get_client_ip()
{
  $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
  foreach ($ip_keys as $key) {
    if (array_key_exists($key, $_SERVER) === true) {
      foreach (explode(',', $_SERVER[$key]) as $ip) {
        $ip = trim($ip);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
          return $ip;
        }
      }
    }
  }
  return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// SETTINGS FUNCTIONS
function get_settings($pdo)
{
  $stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function update_settings($pdo, $data)
{
  $fields = ['instagram', 'linkedin', 'tiktok', 'youtube', 'location', 'portfolio', 'portfolio2'];
  $set = [];
  $params = [];
  foreach ($fields as $field) {
    $set[] = "$field = :$field";
    $params[":$field"] = $data[$field] ?? null;
  }
  $sql = 'UPDATE settings SET ' . implode(', ', $set) . ' WHERE id = 1';
  $stmt = $pdo->prepare($sql);
  return $stmt->execute($params);
}