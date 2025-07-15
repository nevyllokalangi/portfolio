<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Blog';
$pageCSS = '/public/css/blog.css';

ob_start();
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