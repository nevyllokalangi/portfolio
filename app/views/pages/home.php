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
    height: 100vh;
    width: 100vw;
    min-height: 800px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    background-color: var(--primary);
    position: relative;
    overflow: hidden;
    background-image: url('/public/img/home.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
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
    color: rgba(255, 255, 255, 0.7);
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
    border: 2px solid rgba(255, 255, 255, 0.4);
    border-radius: 12px;
    position: relative;
  }

  .scroll-dot {
    width: 4px;
    height: 8px;
    background: var(--primaryFont);
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

  /* Scrolling Text Section */
  .text-wrapper {
    display: flex;
    background: var(--primaryFont);
    overflow: hidden;
    width: 100%;
    padding: 1rem 0;
  }

  .text-wrapper h1 {
    font-size: 3rem;
    color: black;
    letter-spacing: 1px;
    animation: move-rtl 16s linear infinite;
    font-weight: 700;
    text-transform: uppercase;
  }

  @keyframes move-rtl {
    0% {
      transform: translateX(0);
    }

    100% {
      transform: translateX(-100%);
    }
  }
</style>
<main class="content">
  <!-- Hero Section -->

  <section class="hero">
    <div class="hero-content">
      <div>

      </div>
      <div>
        <h1>NEVYLLO</h1>
        <h1>ZAMUEL</h1>
        <h1>KALANGI</h1>
      </div>
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


  <div class="text-wrapper">
    <h1> — NEVYLLO KALANGI — CONTENT CREATOR</h1>
    <h1> — NEVYLLO KALANGI — EVENT MANAGER</h1>
    <h1> — NEVYLLO KALANGI — CREATIVE DEVELOPER</h1>
  </div>
</main>
<!-- Include footer -->
<?php include __DIR__ . '\..\..\..\app\views\partials\footer.php'; ?>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>

<script>
</script>