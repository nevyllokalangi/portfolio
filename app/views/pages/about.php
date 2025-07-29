<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'About';
$pageCSS = '/public/css/about.css';

ob_start();
?>

<main class="content">
  <!-- Hero Section -->
  <section class="about-hero">
    <span class="hero-greeting">Hello, I'm</span>
    <h1 class="hero-name">Nevyllo Z. Kalangi</h1>
    <h2 class="hero-role">And I'm a <span class="highlight-role">Digital Creator & Developer</span></h2>
    <p class="hero-desc">
      I blend creativity and technology to deliver impactful digital experiences. With a passion for storytelling,
      design, and development, I help brands and individuals stand out in the digital world.
    </p>
  </section>
  <!-- Image Slider -->
  <?php include __DIR__ . '/../partials/imageslider.php'; ?>
  <!-- Biography Section -->
  <?php include __DIR__ . '/../partials/biography.php'; ?>
</main>

<style>
  .content {
    padding-bottom: 6rem;
  }

  .about-hero {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    flex: 1;
    padding: 10%;
    background: var(--primary, #0d0d0d);
    color: var(--primaryFont, #ffffe3);
  }

  .hero-greeting {
    font-size: 1.1rem;
    color: var(--accentBlue, #4dabf7);
    font-weight: 500;
  }

  .hero-name {
    font-size: 7rem;
    font-weight: 800;
    color: #fff;
  }

  .hero-role {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--primaryFont, #ffffe3);
    margin-bottom: 0.7rem;
  }

  .highlight-role {
    color: var(--accentBlue, #4dabf7);
    font-weight: 700;
  }

  .hero-desc {
    white-space: normal;
    font-size: 1.08rem;
    color: var(--secondaryFont, #ccccb5);
    margin-bottom: 1.2rem;
  }

  @media (max-width: 900px) {
    .about-hero-inner {
      flex-direction: column;
      gap: 2.2rem;
      padding: 2.5rem 0.5rem;
    }
  }
</style>
<!-- Include footer -->
<?php include __DIR__ . '/../partials/footer.php'; ?>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>