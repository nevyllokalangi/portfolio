<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Project';
$pageCSS = '/public/css/project.css';

// Database connection
$stmt = $pdo->prepare("SELECT * FROM projects ORDER BY date ASC");
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
?>

<main class="content">
  <!-- Content Start -->
  <div class="project-container">
    <div class="project-header">
      <h1>Project Updates</h1>
      <p class="subtitle">News & updates from our team.</p>
    </div>

    <div class="blog-list">
      <?php foreach ($projects as $project):
        $authorNames = explode(',', $project['author_name']);
        $authorAvatars = explode(',', $project['author_avatar']);
        ?>
        <div class="blog-item" data-bg="<?= htmlspecialchars($project['featured_image'] ?? '') ?>">
          <div class="blog-item-header">
            <span class="blog-date"><?= date('F j, Y', strtotime($project['date'])) ?></span>
            <h2 class="blog-title"><?= htmlspecialchars($project['title']) ?></h2>
          </div>
          <div class="blog-excerpt">
            <p><?= htmlspecialchars($project['excerpt']) ?></p>
          </div>
          <div class="blog-meta">
            <div class="author-info">
              <?php foreach ($authorAvatars as $index => $avatar): ?>
                <div class="author-avatar-container">
                  <img src="<?= htmlspecialchars($avatar) ?>" alt="Author" class="author-avatar">
                  <span class="author-tooltip"><?= htmlspecialchars($authorNames[$index] ?? '') ?></span>
                </div>
              <?php endforeach; ?>
            </div>
            <a href="/project/<?= $project['id'] ?>" class="read-more">Read More →</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Background image for hover effect -->
  <div id="background-image-container">
    <img id="background-image" crossorigin="anonymous" alt="">
  </div>
  <!-- Contact Section -->
  <?php include __DIR__ . '\..\..\..\app\views\partials\contact.php'; ?>
</main>

<style>
  .project-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    position: relative;
    z-index: 10;
  }

  .project-header {
    margin-bottom: 3rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 1rem;
    padding-top: 5rem;
  }

  .project-header h1 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
  }

  .subtitle {
    font-family: monospace;
    font-size: 0.875rem;
    opacity: 0.7;
    text-transform: uppercase;
  }

  .blog-list {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
  }

  .blog-item {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    padding-top: 1.5rem;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .blog-item::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 0;
    z-index: -1;
    transition: height 0.3s cubic-bezier(0.445, 0.05, 0.55, 0.95);
  }

  .blog-item:hover::before {
    height: 100%;
  }

  .blog-item:hover {
    color: white;
  }

  .blog-item:hover .read-more {
    color: white;
  }

  .blog-item-header {
    margin-bottom: 1rem;
  }

  .blog-date {
    font-family: monospace;
    font-size: 0.75rem;
    text-transform: uppercase;
    opacity: 0.5;
    display: block;
    margin-bottom: 0.5rem;
    transition: opacity 0.3s ease;
  }

  .blog-item:hover .blog-date {
    opacity: 0.8;
  }

  .blog-title {
    white-space: initial;
    font-size: 1.5rem;
    font-weight: 900;
    margin: 0;
    text-transform: uppercase;
    transition: all 0.3s ease;
  }

  .blog-item:hover .blog-title {
    color: #e63946;
    transform: translateX(10px);
  }

  .blog-excerpt {
    margin-bottom: 1.5rem;
    font-size: 1rem;
    line-height: 1.6;
    transition: all 0.3s ease;
  }

  .blog-item:hover .blog-excerpt {
    transform: translateX(10px);
  }

  .blog-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .author-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .author-avatar-container {
    position: relative;
    display: inline-block;
  }

  .author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .author-avatar-container:hover .author-avatar {
    transform: scale(1.1);
  }

  .author-tooltip {
    visibility: hidden;
    width: auto;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 4px;
    padding: 5px 10px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    font-size: 0.75rem;
    white-space: nowrap;
    text-transform: uppercase;
  }

  .author-tooltip::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
  }

  .author-avatar-container:hover .author-tooltip {
    visibility: visible;
    opacity: 1;
  }

  .read-more {
    font-weight: 700;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
  }

  .read-more:hover {
    transform: translateX(5px);
  }

  /* Background image hover effect */
  #background-image-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    overflow: hidden;
    pointer-events: none;
  }

  #background-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scale(1.2);
    transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94), opacity 0.5s ease;
    opacity: 0;
  }

  /* Responsive adjustments */
  @media (min-width: 768px) {
    .blog-title {
      font-size: 2.5rem;
    }

    .blog-item {
      padding-top: 2rem;
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const blogItems = document.querySelectorAll(".blog-item");
    const backgroundImage = document.getElementById("background-image");

    // Preload images
    const preloadedImages = {};
    blogItems.forEach(item => {
      const bgImage = item.dataset.bg;
      if (bgImage) {
        const img = new Image();
        img.src = bgImage;
        preloadedImages[bgImage] = img;
      }
    });

    // Setup hover events
    blogItems.forEach(item => {
      item.addEventListener("mouseenter", function () {
        const bgImage = this.dataset.bg;
        if (bgImage) {
          backgroundImage.src = bgImage;
          backgroundImage.style.opacity = "0.2";
          backgroundImage.style.transform = "scale(1.0)";
        }
      });

      item.addEventListener("mouseleave", function () {
        backgroundImage.style.opacity = "0";
        backgroundImage.style.transform = "scale(1.2)";
      });
    });
  });
</script>

<!-- Include footer -->
<?php include __DIR__ . '/../partials/footer.php'; ?>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>