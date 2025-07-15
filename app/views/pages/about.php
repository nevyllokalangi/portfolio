<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'About';
$pageCSS = '/public/css/about.css';

ob_start();
?>

<main class="content">
  <!-- Hero Section -->
  <div class="about-hero">
    <div class="featured-wrapper">
      <img src="/public/img/about.png" class="featured-image" alt="About Us">
      <div class="title-wrapper">
        <span class="hero-subtitle">Get to Know Us</span>
        <h1 class="hero-title">Our Story & Vision</h1>
        <button class="cta-button">Learn More</button>
      </div>
    </div>
  </div>

  <!-- Team Section -->
  <div class="about-container">
    <div class="section-header">
      <h2 class="section-title">Meet The Team</h2>
    </div>

    <div class="team-grid">
      <div class="team-member">
        <img src="/public/img/team1.jpg" alt="Team Member">
        <h3>John Doe</h3>
        <p>Founder & CEO</p>
      </div>
      <div class="team-member">
        <img src="/public/img/team2.jpg" alt="Team Member">
        <h3>Jane Smith</h3>
        <p>Creative Director</p>
      </div>
      <div class="team-member">
        <img src="/public/img/team3.jpg" alt="Team Member">
        <h3>Mike Johnson</h3>
        <p>Lead Developer</p>
      </div>
      <div class="team-member">
        <img src="/public/img/team4.jpg" alt="Team Member">
        <h3>Sarah Williams</h3>
        <p>Marketing Head</p>
      </div>
    </div>
  </div>

  <!-- Image Slider -->
  <?php include __DIR__ . '/../partials/imageslider.php'; ?>
  <!-- Portfolio Download Section -->
  <?php include __DIR__ . '/../partials/portfolio.php'; ?>
  <!-- Biography Section -->
  <?php include __DIR__ . '/../partials/biography.php'; ?>

</main>

<style>
  .content {
    padding-bottom: 6rem;
  }

  .about-hero {
    position: relative;
    height: 100vh;
  }

  .featured-wrapper {
    position: relative;
    height: 100%;
  }

  .featured-image {
    width: 100%;
    height: 100%;
    filter: brightness(30%);
    object-fit: cover;
    object-position: center;
  }

  .title-wrapper {
    position: absolute;
    bottom: 4rem;
    left: 4rem;
    max-width: 60%;
  }

  .hero-subtitle {
    display: block;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.8);
  }

  .hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin: 0 0 2rem 0;
    line-height: 1.2;
  }

  .cta-button {
    background: var(--accentBlue);
    color: white;
    font-size: 1.1rem;
    padding: 0.8rem 2.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .cta-button:hover {
    background: #c4050f;
    transform: translateY(-2px);
  }

  .about-container {
    padding: 4rem;
    max-width: 1400px;
    margin: 0 auto;
  }

  .section-header {
    margin-bottom: 3rem;
  }

  .section-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
  }

  .team-grid,
  .values-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    margin-bottom: 6rem;
  }

  .team-member img,
  .value-card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    margin-bottom: 1rem;
    -webkit-box-reflect: below 5px linear-gradient(transparent, transparent, rgba(0, 0, 0, 0.4));
    transition: all 0.5s ease;
  }

  .team-member img:hover,
  .value-card img:hover {
    transform: scale(1.05);
  }

  .team-member h3,
  .value-card h3 {
    font-size: 1.5rem;
    margin: 0.5rem 0;
  }

  .team-member p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
  }

  @media (max-width: 1024px) {

    .team-grid,
    .values-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .title-wrapper {
      max-width: 80%;
      left: 2rem;
    }

    .hero-title {
      font-size: 2.8rem;
    }
  }

  @media (max-width: 768px) {
    .about-container {
      padding: 2rem;
    }

    .title-wrapper {
      text-align: center;
      left: 0;
      right: 0;
      margin: 0 auto;
      bottom: 2rem;
    }

    .hero-title {
      font-size: 2.2rem;
    }

    .team-grid,
    .values-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
<!-- Include footer -->
<?php include __DIR__ . '/../partials/footer.php'; ?>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>