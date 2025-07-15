<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Test Page';
$pageCSS = '/public/css/test.css';

ob_start();
?>
<!-- Content Start -->
<main class="content">
  <div class="seperator"></div>
  <div class="test-container">
    <h1>Test Page</h1>
    <p>This is a test page for development purposes.</p>
  </div>
</main>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>