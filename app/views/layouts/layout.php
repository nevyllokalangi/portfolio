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
    echo getRegularLayoutSpecific();
    ?>
  </style>
</head>

<body>
  <div class="layout">
    <!-- Include header -->
    <?php include __DIR__ . '/../partials/header.php'; ?>
    <?php echo isset($content) ? $content : ''; ?>
  </div>
  <script>
    // Prefetch internal links on hover/touch
    document.addEventListener('DOMContentLoaded', function () {
      const isInternal = href => href.startsWith('/') && !href.startsWith('//') && !href.startsWith('/#') && !href.startsWith('#');
      const prefetchCache = new Set();
      function prefetch(url) {
        if (!prefetchCache.has(url)) {
          prefetchCache.add(url);
          const link = document.createElement('link');
          link.rel = 'prefetch';
          link.href = url;
          document.head.appendChild(link);
        }
      }
      document.body.addEventListener('mouseover', function (e) {
        const a = e.target.closest('a');
        if (a && isInternal(a.getAttribute('href'))) {
          prefetch(a.href);
        }
      }, { passive: true });
      document.body.addEventListener('touchstart', function (e) {
        const a = e.target.closest('a');
        if (a && isInternal(a.getAttribute('href'))) {
          prefetch(a.href);
        }
      }, { passive: true });
    });
    // Aggressive cache via service worker
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js').catch(function (err) {
          // Registration failed
        });
      });
    }
  </script>
</body>

</html>