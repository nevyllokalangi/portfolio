<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../functions/helpers.php';
$settings = get_settings($pdo);
$pageTitle = 'Home';
$pageCSS = '/public/css/home.css';
$pageJS = '/public/js/home.js';
?>
<?php ob_start(); ?>

<style>
  section {
    padding: 5rem 10%;
    position: relative;
  }

  /* Base Styles */
  .hero {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100vw;
    min-height: 800px;
  }

  .hero-subtitle {
    width: 30%;
  }

  .hero-title {
    width: 100%;
  }

  .hero-bg {
    position: absolute;
    left: 50%;
    top: 50%;
    min-width: 100%;
    min-height: 100%;
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    z-index: -1;
  }

  /* Enhanced Scroll Indicator */
  .hero-scroll {
    position: absolute;
    bottom: 2.5rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--color-text-secondary);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .scroll-text {
    margin-bottom: 0.5rem;
    animation: fadeInOut 2.5s infinite;
  }

  .scroll-animation {
    width: 24px;
    height: 40px;
    border: 2px solid var(--color-border);
    border-radius: 12px;
    position: relative;
  }

  .scroll-dot {
    width: 4px;
    height: 8px;
    background: var(--color-text-primary);
    border-radius: 2px;
    position: absolute;
    top: 6px;
    left: 50%;
    transform: translateX(-50%);
    animation: scrollBounce 2s infinite;
  }

  @keyframes fadeInOut {

    0%,
    100% {
      opacity: 0.5;
    }

    50% {
      opacity: 1;
    }
  }

  @keyframes scrollBounce {
    0% {
      transform: translate(-50%, 0);
      opacity: 0;
    }

    40% {
      opacity: 1;
    }

    80% {
      transform: translate(-50%, 12px);
      opacity: 0;
    }

    100% {
      opacity: 0;
    }
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .hero-cta {
      flex-direction: column;
      gap: 1rem;
    }

    .btn-primary,
    .btn-secondary {
      width: 100%;
      justify-content: center;
    }

    .hero-title {
      font-size: 2.5rem;
    }
  }
</style>
<main class="content">
  <!-- Hero Section -->









  <section class="hero">
    <video class="hero-bg" src="public/img/home-bg.mp4" muted loop autoplay></video>
    <div class="hero-content">
      <img class="hero-subtitle" src="public/img/home-subtitle.svg" draggable="false" loading="lazy"
        oncontextmenu="return false;" />
      <img class="hero-title" src="public/img/home-title.svg" draggable="false" loading="lazy"
        oncontextmenu="return false;" />
    </div>

    <!-- Enhanced scroll indicator -->
    <div class="hero-scroll" aria-hidden="true">
      <span class="scroll-text">Discover More</span>
      <div class="scroll-animation">
        <div class="scroll-dot"></div>
      </div>
    </div>
  </section>
  <!-- Project Section -->
  <?php include __DIR__ . '\..\..\..\app\views\partials\latestproject.php'; ?>

  <!-- Biography Section -->
  <?php include __DIR__ . '\..\..\..\app\views\partials\scrollingbiography.php'; ?>

  <!-- FAQ Section -->
  <?php include __DIR__ . '\..\..\..\app\views\partials\faq.php'; ?>

  <!-- Contact Section -->
  <?php include __DIR__ . '\..\..\..\app\views\partials\contact.php'; ?>
</main>
<!-- Include footer -->
<?php include __DIR__ . '\..\..\..\app\views\partials\footer.php'; ?>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>