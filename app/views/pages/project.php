<?php
require_once __DIR__ . '/../../../config.php';
$pageTitle = 'Project';
$pageCSS = '/public/css/project.css';

// Get projects from database
$projects = $pdo->query("SELECT * FROM projects ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<main class="content">
  <section class="project-container">
    <h1 class="project-title">Project</h1>
    <p class="project-subtitle">Step back in time and walk the path of my projects, where each creation carries a
      memory, a lesson, and a piece of who I am.</p>

    <div class="project-blog-list">
      <?php foreach ($projects as $project): ?>
        <div class="project-blog-item" data-bg="<?= htmlspecialchars($project['featured_image'] ?? '') ?>">
          <div class="project-blog-item-header">
            <span class="project-blog-date"><?= date('F j, Y', strtotime($project['date'])) ?></span>
            <h2 class="project-blog-title"><?= htmlspecialchars($project['title']) ?></h2>
          </div>
          <div class="project-blog-excerpt">
            <p><?= htmlspecialchars($project['excerpt']) ?></p>
          </div>
          <div class="project-blog-meta">
            <div class="project-author-info">
              <?php
              $authors = array_combine(
                explode(',', $project['author_name']),
                explode(',', $project['author_avatar'])
              );
              foreach ($authors as $name => $avatar): ?>
                <div class="project-author-avatar-container">
                  <img src="<?= htmlspecialchars($avatar) ?>" alt="Author" class="project-author-avatar">
                  <span class="project-author-tooltip"><?= htmlspecialchars($name) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
            <a href="/project/<?= $project['id'] ?>" class="project-read-more">Read More →</a>
          </div>
          <div id="project-background-image-container">
            <img id="project-background-image" crossorigin="anonymous" alt="">
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </section>

  <?php include __DIR__ . '/../partials/contact.php'; ?>
</main>

<style>
  .project-container {
    padding: 16vh 20vw;
  }

  .project-title {
    font-family: Roslindale, serif;
    font-size: 8rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 2rem;
    letter-spacing: -0.05em;
  }


  .project-subtitle {
    font-family: monospace;
    font-size: 0.875rem;
    opacity: 0.7;
    text-transform: uppercase;
  }

  .project-blog-list {
    display: grid;
    gap: 3rem;
  }

  .project-blog-item {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    padding-top: 1.5rem;
    cursor: pointer;
  }

  .project-blog-item:hover {
    color: white;
  }

  .project-blog-item:hover .project-read-more {
    color: white;
  }

  .project-blog-date {
    font-family: monospace;
    font-size: 0.75rem;
    text-transform: uppercase;
    opacity: 0.5;
    margin-bottom: 0.5rem;
    display: block;
  }

  .project-blog-item:hover .project-blog-date {
    opacity: 0.8;
  }

  .project-blog-title {
    font-size: 1.5rem;
    font-weight: 900;
    margin: 0;
    text-transform: uppercase;
    transition: all 0.3s ease;
  }

  .project-blog-item:hover .project-blog-title {
    color: #e63946;
  }

  .project-blog-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .project-author-info {
    display: flex;
    gap: 0.5rem;
  }

  .project-author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
  }

  .project-author-tooltip {
    visibility: hidden;
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    position: absolute;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    font-size: 0.75rem;
    white-space: nowrap;
    text-transform: uppercase;
  }

  .project-author-avatar-container:hover .project-author-tooltip {
    visibility: visible;
    opacity: 1;
  }

  .project-read-more {
    font-weight: 700;
    text-decoration: none;
    color: inherit;
  }

  #project-background-image-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
  }

  #project-background-image {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scale(1.2);
    opacity: 0;
  }

  .project-container {
    position: relative;
  }

  @media (min-width: 768px) {
    .project-blog-title {
      font-size: 2.5rem;
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const blogItems = document.querySelectorAll(".project-blog-item");
    const backgroundImage = document.getElementById("project-background-image");

    blogItems.forEach(item => {
      item.addEventListener("mouseenter", function () {
        if (this.dataset.bg) {
          backgroundImage.src = this.dataset.bg;
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

<?php include __DIR__ . '/../partials/footer.php'; ?>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>