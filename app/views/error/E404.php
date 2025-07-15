<!-- Page Config -->
<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Page Not Found';
$pageCSS = '';
?>

<?php ob_start(); ?>
<main class="content">
  <!-- Content Start -->
  <!-- Content End -->
</main>
<!-- Include footer -->
<?php include __DIR__ . '\..\..\..\app\views\partials\footer.php'; ?>
<?php $content = ob_get_clean(); ?>

<?php
// Include the base template with the specific content
include __DIR__ . '/../layouts/layout.php';
?>