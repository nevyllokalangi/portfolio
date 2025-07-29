<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Blog';
$pageCSS = '/public/css/blog.css';

ob_start();
require_login();
if (!in_array($_SESSION['role'] ?? '', [ROLE_EDITOR, ROLE_ADMIN])) {
  header('HTTP/1.0 403 Forbidden');
  exit('Access denied: Only editors and admins can access this page.');
}
?>
<!-- Content Start -->
<main class="content">
  <div class="seperator"></div>
  <div class="blog-container">
    <h1>Blog</h1>
    <p>Coming soon...</p>
  </div>
</main>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>