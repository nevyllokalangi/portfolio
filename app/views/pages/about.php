<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'About';
$pageCSS = '/public/css/about.css';

ob_start();
?>

<main class="content">
  <!-- Hero Section -->
  <section class="about-hero">
    <div class="about-content">
      <h1 class="about-title">About Me</h1>
      <p class="about-subtitle">Computer Science Student · Creative Developer · Digital Storyteller</p>
      <div class="about-desc">
        <div class="about-paragraph">Nevyllo is a Computer Science student currently studying in China, where he
          balances
          a demanding academic life with active roles in student organizations like PPIT. His interests live at the
          intersection of technology, media, and leadership — blending logic with storytelling.<br><br>
          With a passion for design, web development, and digital content, he often finds
          himself building tools, platforms, and visual experiences that are both functional and expressive. From
          structuring efficient workflows for multimedia departments to fine-tuning UI aesthetics with dark themes and
          accent hues, Nevyllo approaches tech projects with both discipline and flair.<br><br>
          He is deeply curious about how technology can empower individuals and communities —
          whether through generative art, automation systems, or impactful visual communication. Outside the
          classroom, he frequently shares his experience of student life abroad, juggling responsibilities, and
          creative work through engaging social media content.<br><br>
          Originally from Indonesia, Nevyllo continues to grow his voice as both a technical
          creator and cultural bridge — building, sharing, and leading with purpose.<br><br>
        </div>
      </div>
  </section>
  <!-- Biography Section -->
  <?php include __DIR__ . '/../partials/biography.php'; ?>
  <!-- Contact Section -->
  <?php include __DIR__ . '\..\..\..\app\views\partials\contact.php'; ?>
</main>

<style>
  .about-hero {
    padding: 16vh 20vw;
    background: var(--color-bg-primary);
    color: var(--color-text-primary);
  }

  .about-title {
    font-family: Roslindale, serif;
    font-size: 8rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 2rem;
    letter-spacing: -0.05em;
  }

  .about-subtitle {
    font-family: monospace;
    font-size: 1.25rem;
    font-weight: 500;
    margin-bottom: 2rem;
    color: var(--color-text-secondary);
  }

  .about-desc {
    display: grid;
    gap: 1.5rem;
  }

  .about-paragraph {
    white-space: initial;
    font-size: 1.1rem;
    line-height: 1.7;
    margin: 0;
  }

  .about-paragraph:first-of-type::first-letter {
    initial-letter: 2;
    font-weight: 700;
    margin-right: 0.5rem;
    color: var(--color-accent);
  }

  @media (max-width: 768px) {
    .about-hero {
      padding: 15% 5%;
    }

    .about-title {
      font-size: 2.5rem;
    }

    .about-paragraph {
      font-size: 1rem;
    }
  }
</style>
<!-- Include footer -->
<?php include __DIR__ . '/../partials/footer.php'; ?>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>