<?php
require_once __DIR__ . '/../../../config.php';
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

  .hero-content {
    max-width: 800px;
    padding: 0 2rem;
    z-index: 2;
  }

  .hero-title {
    font-family: "defExBoldFont", sans-serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    line-height: 1.2;
    margin-bottom: 1.5rem;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s 0.2s forwards;
  }

  .hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.5rem);
    color: var(--textSecondary);
    margin-bottom: 3rem;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s 0.4s forwards;
  }

  .hero-cta {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s 0.6s forwards;
  }

  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Enhanced Primary Button */
  .btn-primary {
    display: inline-flex;
    align-items: center;
    position: relative;
    padding: 1.25rem 2.5rem;
    border-radius: 50px;
    font-family: "defBoldFont", sans-serif;
    letter-spacing: 0.5px;
    overflow: hidden;
    isolation: isolate;
    transition: color 0.4s ease;
    color: var(--primaryFont);
  }

  .btn-primary .btn__circle {
    position: absolute;
    inset: 0;
    border-radius: 50px;
    box-shadow: 0 0 0 2px var(--accentOrange);
    transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
    z-index: -1;
  }

  .btn-primary .btn__white-circle {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--primaryFont);
    display: grid;
    place-items: center;
    transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
    z-index: -1;
  }

  .btn-primary .btn__text {
    transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
  }

  .btn-primary .btn__hover-text {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-30px, -50%);
    opacity: 0;
    transition: all 0.6s cubic-bezier(0.65, 0, 0.35, 1);
    color: var(--primary);
  }

  .btn-primary:hover {
    color: var(--primary);
  }

  .btn-primary:hover .btn__circle {
    transform: scale(1.2);
    opacity: 0;
  }

  .btn-primary:hover .btn__white-circle {
    transform: translate(-50%, -50%) scale(1.5);
  }

  .btn-primary:hover .btn__text {
    transform: translateX(15px);
    opacity: 0;
  }

  .btn-primary:hover .btn__hover-text {
    transform: translate(-50%, -50%);
    opacity: 1;
  }

  /* New Secondary Button */
  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.25rem 2rem;
    border-radius: 50px;
    font-family: "defBoldFont", sans-serif;
    letter-spacing: 0.5px;
    background: transparent;
    color: var(--primaryFont);
    border: 2px solid rgba(255, 255, 255, 0.2);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
  }

  .btn-secondary::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    z-index: -1;
    opacity: 0;
    transition: opacity 0.4s ease;
  }

  .btn-secondary:hover {
    border-color: rgba(255, 255, 255, 0.4);
  }

  .btn-secondary:hover::before {
    opacity: 1;
  }

  .btn-secondary__icon {
    width: 20px;
    height: 20px;
    transition: transform 0.4s ease;
  }

  .btn-secondary:hover .btn-secondary__icon {
    transform: rotate(45deg);
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
      <h1 class="hero-title">Crafting Digital Stories That <span class="highlight">Inspire</span></h1>
      <p class="hero-subtitle">Photographer • Writer • Digital Creator</p>

      <div class="hero-cta">
        <!-- Primary CTA with enhanced animation -->
        <a href="/work" class="btn btn-primary" aria-label="View my portfolio work">
          <span class="btn__circle"></span>
          <span class="btn__white-circle">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2">
              <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
          </span>
          <span class="btn__text">Explore My Portfolio</span>
          <span class="btn__hover-text">See Recent Projects →</span>
        </a>

        <!-- Secondary CTA with new design -->
        <a href="/contact" class="btn-secondary" aria-label="Get in touch">
          <span class="btn-secondary__text">Let's Collaborate</span>
          <span class="btn-secondary__icon">
            <svg viewBox="0 0 24 24">
              <path d="M22 12h-4M6 12H2M12 6V2M12 22v-4" />
            </svg>
          </span>
        </a>
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
  // 3D Gallery Navigation
  document.addEventListener('DOMContentLoaded', function () {
    const ring = document.querySelector('.ring');
    const prevBtn = document.querySelector('.nav-button.prev');
    const nextBtn = document.querySelector('.nav-button.next');
    let currentRotation = 0;
    const rotationIncrement = 45;

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        currentRotation -= rotationIncrement;
        ring.style.animation = 'none';
        ring.style.transform = `translate(-50%, -50%) rotateY(${currentRotation}deg)`;
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        currentRotation += rotationIncrement;
        ring.style.animation = 'none';
        ring.style.transform = `translate(-50%, -50%) rotateY(${currentRotation}deg)`;
      });
    }

    // Pause animation on hover
    const gallery = document.querySelector('.gallery-section');
    if (gallery) {
      gallery.addEventListener('mouseenter', () => {
        ring.style.animationPlayState = 'paused';
      });

      gallery.addEventListener('mouseleave', () => {
        ring.style.animationPlayState = 'running';
      });
    }
  });
</script>