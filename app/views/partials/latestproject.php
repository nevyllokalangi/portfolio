<section class="projects-section">
  <div class="section-header">
    <h2>Latest <span class="highlight">Projects</span></h2>
    <p>Creativity, Technology, and Digital Landscape</p>
  </div>

  <div class="projects-grid">
    <?php
    // Get latest 3 projects
    $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY date DESC LIMIT 3");
    $stmt->execute();
    $latestProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($latestProjects as $project):
      $authorNames = explode(',', $project['author_name']);
      $authorAvatars = explode(',', $project['author_avatar']);
      ?>
      <article class="project-card">
        <div class="card-image">
          <img src="<?= htmlspecialchars($project['featured_image']) ?>" alt="<?= htmlspecialchars($project['title']) ?>"
            loading="lazy" />
          <span class="card-category"><?= date('M Y', strtotime($project['date'])) ?></span>
        </div>
        <div class="card-content">
          <h3><?= htmlspecialchars($project['title']) ?></h3>
          <p class="card-excerpt"><?= htmlspecialchars($project['excerpt']) ?></p>
          <div class="card-meta">
            <div class="author-info">
              <?php foreach ($authorAvatars as $index => $avatar): ?>
                <div class="author-avatar-container">
                  <img src="<?= htmlspecialchars($avatar) ?>" alt="Author" class="author-avatar">
                  <span class="author-tooltip"><?= htmlspecialchars($authorNames[$index] ?? '') ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <a href="/project/<?= $project['id'] ?>" class="card-link">View Project</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>

  <div class="section-footer">
    <div class="button">
      <a href="/project">
        View All Projects
        <span class="shift">›</span>
      </a>
      <div class="mask"></div>
    </div>
  </div>
</section>

<style>
  /* Projects Section */
  .projects-section {
    padding: 6rem 2rem;
    background-color: var(--bg-body);
    background-image: var(--bg-body-gradient);
  }

  .section-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .section-header h2 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    color: white;
  }

  .section-header .highlight {
    color: var(--tertiaryFont);
  }

  .section-header p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.1rem;
  }

  .projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto 3rem;
  }

  .project-card {
    background: rgba(30, 30, 30, 0.8);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  .project-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
  }

  .card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
  }

  .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .project-card:hover .card-image img {
    transform: scale(1.05);
  }

  .card-category {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--tertiaryFont);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
  }

  .card-content {
    padding: 1.5rem;
  }

  .card-content h3 {
    margin-bottom: 0.8rem;
    font-size: 1.2rem;
    color: white;
  }

  .card-excerpt {
    white-space: initial;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 1.2rem;
    line-height: 1.5;
  }

  .card-meta {
    margin-bottom: 1.2rem;
  }

  .author-info {
    display: flex;
    gap: 0.5rem;
  }

  .author-avatar-container {
    position: relative;
  }

  .author-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
  }

  .author-avatar-container:hover .author-avatar {
    transform: scale(1.1);
  }

  .author-tooltip {
    visibility: hidden;
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 0.7rem;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .author-avatar-container:hover .author-tooltip {
    visibility: visible;
    opacity: 1;
  }

  .card-link {
    display: inline-block;
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
  }

  .card-link:hover {
    color: var(--tertiaryFont);
  }

  .section-footer {
    text-align: center;
    margin-top: 2rem;
  }

  /* Button Styles */
  :root {
    --bg-body: rgba(25, 25, 25, 1);
    --bg-body-gradient: radial-gradient(30% 90% ellipse at bottom center, rgba(0, 0, 0, 0), rgba(0, 0, 0, 1) 100%);
    --bg-body-hover: rgba(33, 33, 33, 1);
    --bg-button: rgba(255, 255, 255, 0);
    --bg-button-hover: rgba(255, 255, 255, 0.05);
    --bg-button-active: rgba(255, 255, 255, 1.0);
    --bg-mask: rgba(255, 255, 255, 0.5);
    --bg-mask-hover: rgba(255, 255, 255, 1.0);
    --border-button: rgba(255, 255, 255, 0.2);
    --border-button-hover: rgba(255, 255, 255, 1.0);
    --color-button: rgba(255, 255, 255, 0.6);
    --color-button-hover: rgba(255, 255, 255, 1.0);
    --color-button-active: var(--bg-body);
    --transition-easing: cubic-bezier(0.19, 1, 0.22, 1);
    --shadow-button-hover: 0 0 0.3125rem rgba(255, 255, 255, 0.8);
  }

  .button {
    background-color: var(--bg-button);
    border: 0.125rem solid var(--border-button);
    cursor: pointer;
    letter-spacing: 0.2125rem;
    line-height: 1;
    overflow: hidden;
    padding: 1.25rem 1.875rem;
    position: relative;
    text-align: center;
    text-transform: uppercase;
    transition:
      background-color 0.3s var(--transition-easing),
      border 1s var(--transition-easing),
      color 0.6s var(--transition-easing);
    user-select: none;
    display: inline-block;
  }

  .button a {
    color: var(--color-button);
    font-family: "Varela Round", sans-serif;
    position: relative;
    text-decoration: none;
    white-space: nowrap;
    z-index: 2;
  }

  .button .mask {
    background-color: var(--bg-mask);
    height: 6.25rem;
    position: absolute;
    transform: translate3d(-120%, -3.125rem, 0) rotate3d(0, 0, 1, 45deg);
    transition: all 1.1s var(--transition-easing);
    width: 12.5rem;
    z-index: 1;
  }

  .button .shift {
    display: inline-block;
    transition: all 1.1s var(--transition-easing);
    vertical-align: text-top;
  }

  .button:hover {
    background-color: var(--bg-button-hover);
    border-color: var(--border-button-hover);
    box-shadow: var(--shadow-button-hover);
  }

  .button:hover a {
    color: var(--color-button-hover);
  }

  .button:hover .mask {
    background-color: var(--bg-mask-hover);
    transform: translate3d(120%, -6.25rem, 0) rotate3d(0, 0, 1, 90deg);
  }

  .button:hover .shift {
    transform: translateX(0.3125rem);
  }

  .button:active {
    background-color: var(--bg-button-active);
  }

  .button:active a {
    color: var(--color-button-active);
  }
</style>

<script>
  const body = document.body;
  const btn = document.querySelector('.button');

  if (btn) {
    btn.addEventListener('mouseenter', () => {
      body.classList.add('hover');
    });

    btn.addEventListener('mouseleave', () => {
      body.classList.remove('hover');
    });
  }
</script>