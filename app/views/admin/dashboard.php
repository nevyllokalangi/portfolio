<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

$pageTitle = 'Dashboard';
$pageCSS = '';
?>

<?php ob_start(); ?>
<main class="content">
  <!-- Content Start -->
  <!-- Content End -->
</main>
<?php $content = ob_get_clean(); ?>

<?php include __DIR__ . '/../layouts/adminlayout.php'; ?>