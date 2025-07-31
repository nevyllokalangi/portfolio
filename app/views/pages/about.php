<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'About';
$pageCSS = '/public/css/about.css';

ob_start();
?>

<main class="content">
  <!-- Hero Section -->
  <section class="about-hero">
    <div>About</div><br>
    <div class="about-desc">Nevyllo is a Computer Science student currently studying in China, where he balances a
      demanding academic life with active roles in student organizations like PPIT. His interests live at the
      intersection of technology, media,
      and leadership — blending logic with storytelling. <br><br>With a passion for design, web development, and digital
      content, he often finds himself building tools, platforms,
      and visual experiences that are both functional and expressive. From structuring efficient workflows for
      multimedia departments to fine-tuning UI aesthetics with dark themes and accent hues, Nevyllo approaches tech
      projects with both discipline and flair.<br><br>He is deeply curious about how technology can empower individuals
      and
      communities — whether through generative
      art, automation systems, or impactful visual communication. Outside the classroom, he frequently shares his
      experience of student life abroad, juggling responsibilities, and creative work through engaging social media
      content.<br><br>Originally from Indonesia, Nevyllo continues to grow his voice as both a technical creator and
      cultural bridge —
      building, sharing, and leading with purpose.<br><br>
    </div>
  </section>
  <!-- Image Slider -->
  <?php include __DIR__ . '/../partials/imageslider.php'; ?>
  <!-- Biography Section -->
  <?php include __DIR__ . '/../partials/biography.php'; ?>
  <!-- Contact Section -->
  <?php include __DIR__ . '\..\..\..\app\views\partials\contact.php'; ?>
</main>

<style>
  .about-hero {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    flex: 1;
    padding: 10%;
    background: var(--color-bg-primary);
    color: var(--color-text-primary);
  }

  .about-desc {
    white-space: normal;
  }
</style>
<!-- Include footer -->
<?php include __DIR__ . '/../partials/footer.php'; ?>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>