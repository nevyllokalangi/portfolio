<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/styleconfig.php';
?>
<!DOCTYPE html>
<html>

<head lang="en">
  <!-- Layout Config -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">

  <link rel="icon" type="image/x-icon" media="(prefers-color-scheme: dark)" href="/public/img/favicon.png" />
  <link rel="icon" type="image/x-icon" media="(prefers-color-scheme: light)" href="/public/img/favicon.png" />

  <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet" />
  <!-- Layout Config -->

  <!-- Page Config -->
  <meta name="description" content="<?php echo htmlspecialchars($pageTitle); ?>" />
  <title>NZK | <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'NEVYLLO'; ?></title>
  <!-- Page Config -->
  <style type="text/css">
    <?php
    echo getVariables();
    echo getCommonStyles();
    echo getAdminLayoutSpecific();
    ?>
  </style>
</head>

<body>
  <div class="layout">
    <!-- Include header -->
    <?php include __DIR__ . '/../partials/header.php'; ?>
    <?php echo isset($content) ? $content : ''; ?>
  </div>

  <!-- Mobile Restriction Message -->
  <div class="mobile-restriction">
    <div class="mobile-restriction-icon">
      💻
    </div>
    <h1>Admin Panel</h1>
    <p>This admin panel requires a laptop or larger screen. Please use a device with a minimum width of 1024px to access
      admin features.</p>
    <a href="/" class="back-btn">Go Back Home</a>
  </div>
</body>

</html>