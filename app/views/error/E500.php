<!-- Page Config -->
<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Error 500';
$pageCSS = '';
?>

<?php ob_start(); ?>
<main class="content">
  <!-- Content Start -->
  <!-- Content End -->
</main>
</main>
<?php $content = ob_get_clean(); ?>

<?php include __DIR__ . '/../layouts/layout.php'; ?>