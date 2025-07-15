<?php
require_once __DIR__ . '/../../../config.php';
// Page Configuration
$pageTitle = 'Gallery';
$pageCSS = '/public/css/gallery.css';

// Fetch images from database
try {
  $stmt = $pdo->prepare("SELECT * FROM gallery ORDER BY display_order ASC");
  $stmt->execute();
  $galleryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $galleryItems = [];
  error_log("Database error: " . $e->getMessage());
}

ob_start();
?>
<!-- Content Start -->
<main class="content">
  <div class="gallery-container" id="gallery-container">
    <div class="masonry-grid" id="masonry-grid">
      <?php foreach ($galleryItems as $item): ?>
        <div class="masonry-item" data-id="<?= $item['id'] ?>">
          <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>"
            loading="lazy" draggable="false" data-title="<?= htmlspecialchars($item['title']) ?>"
            data-description="<?= htmlspecialchars($item['description']) ?>">
          <div class="item-overlay">
            <h3><?= htmlspecialchars($item['title']) ?></h3>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Lightbox Modal -->
  <div id="lightbox" class="lightbox">
    <div class="lightbox-content">
      <span class="close-btn">&times;</span>
      <img id="lightbox-image" src="" alt="" draggable="false">
      <div class="lightbox-info">
        <h2 id="lightbox-title"></h2>
        <p id="lightbox-description"></p>
      </div>
    </div>
  </div>
</main>

<style>
  .gallery-container {
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    position: relative;
    -webkit-overflow-scrolling: touch;
  }

  .masonry-grid {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    grid-auto-rows: 10px;
    padding: 20px;
    width: max-content;
    transform-origin: 0 0;
    will-change: transform;
  }

  .masonry-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    cursor: grab;
    transition: transform 0.3s ease;
    user-select: none;
    -webkit-user-drag: none;
  }

  .masonry-item:hover {
    transform: scale(1.02);
  }

  .masonry-item img {
    width: 100%;
    height: auto;
    display: block;
    pointer-events: none;
    user-select: none;
    -webkit-user-drag: none;
  }

  .item-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 100%);
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }

  .masonry-item:hover .item-overlay {
    opacity: 1;
  }

  .item-overlay h3 {
    color: white;
    font-size: 16px;
    margin: 0;
  }

  /* Lightbox styles */
  .lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    z-index: 1000;
    justify-content: center;
    align-items: center;
  }

  .lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    display: flex;
    flex-direction: column;
  }

  .lightbox-content img {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
  }

  .lightbox-info {
    background: #111;
    padding: 20px;
    color: white;
  }

  .close-btn {
    position: absolute;
    top: -40px;
    right: 0;
    color: white;
    font-size: 35px;
    cursor: pointer;
  }

  /* Dragging styles */
  .is-dragging {
    cursor: grabbing;
  }
</style>

<script>
  // Initialize Masonry layout
  function initMasonry() {
    const grid = document.getElementById('masonry-grid');
    const items = grid.querySelectorAll('.masonry-item');

    items.forEach(item => {
      const img = item.querySelector('img');
      img.onload = function () {
        const rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows'));
        const rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap'));
        const rowSpan = Math.ceil((img.height + rowGap) / (rowHeight + rowGap));
        item.style.gridRowEnd = `span ${rowSpan}`;
      };

      // Ensure images are loaded
      if (img.complete) img.onload();
    });
  }

  // Initialize dragging functionality
  function initDragging() {
    const container = document.getElementById('gallery-container');
    const grid = document.getElementById('masonry-grid');
    let isDragging = false;
    let startX, startY;
    let scrollX = 0, scrollY = 0;
    let velocityX = 0, velocityY = 0;
    let lastX, lastY;
    let lastTime;
    let animationId;
    let scale = 1;

    // Touch and mouse event handlers
    const handleStart = (clientX, clientY) => {
      isDragging = true;
      container.classList.add('is-dragging');
      startX = clientX - scrollX;
      startY = clientY - scrollY;
      lastX = clientX;
      lastY = clientY;
      lastTime = Date.now();
      cancelAnimationFrame(animationId);
    };

    const handleMove = (clientX, clientY) => {
      if (!isDragging) return;

      // Calculate velocity for momentum
      const now = Date.now();
      const deltaTime = now - lastTime;
      if (deltaTime > 0) {
        velocityX = (clientX - lastX) / deltaTime;
        velocityY = (clientY - lastY) / deltaTime;
      }
      lastX = clientX;
      lastY = clientY;
      lastTime = now;

      // Update scroll position
      scrollX = clientX - startX;
      scrollY = clientY - startY;

      // Apply transform
      grid.style.transform = `translate(${scrollX}px, ${scrollY}px) scale(${scale})`;
    };

    const handleEnd = () => {
      if (!isDragging) return;
      isDragging = false;
      container.classList.remove('is-dragging');
      applyMomentum();
    };

    // Mouse events
    container.addEventListener('mousedown', (e) => {
      e.preventDefault();
      handleStart(e.clientX, e.clientY);
    });

    window.addEventListener('mousemove', (e) => {
      handleMove(e.clientX, e.clientY);
    });

    window.addEventListener('mouseup', handleEnd);

    // Touch events
    container.addEventListener('touchstart', (e) => {
      const touch = e.touches[0];
      handleStart(touch.clientX, touch.clientY);
    }, { passive: false });

    window.addEventListener('touchmove', (e) => {
      e.preventDefault();
      const touch = e.touches[0];
      handleMove(touch.clientX, touch.clientY);
    }, { passive: false });

    window.addEventListener('touchend', handleEnd);

    // Momentum animation
    function applyMomentum() {
      const deceleration = 0.95;
      const minVelocity = 0.1;

      velocityX *= deceleration;
      velocityY *= deceleration;

      if (Math.abs(velocityX) > minVelocity || Math.abs(velocityY) > minVelocity) {
        scrollX += velocityX * 20;
        scrollY += velocityY * 20;
        grid.style.transform = `translate(${scrollX}px, ${scrollY}px) scale(${scale})`;
        animationId = requestAnimationFrame(applyMomentum);
      }
    }

    // Handle window resize
    window.addEventListener('resize', () => {
      // Keep content centered on resize
      scrollX = (container.clientWidth - grid.clientWidth * scale) / 2;
      scrollY = (container.clientHeight - grid.clientHeight * scale) / 2;
      grid.style.transform = `translate(${scrollX}px, ${scrollY}px) scale(${scale})`;
    });
  }

  // Initialize lightbox
  function initLightbox() {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-image');
    const lightboxTitle = document.getElementById('lightbox-title');
    const lightboxDesc = document.getElementById('lightbox-description');
    const closeBtn = document.querySelector('.close-btn');

    document.querySelectorAll('.masonry-item').forEach(item => {
      item.addEventListener('click', function (e) {
        // Only open lightbox if not dragging
        if (!document.getElementById('gallery-container').classList.contains('is-dragging')) {
          const img = this.querySelector('img');
          lightboxImg.src = img.src;
          lightboxImg.alt = img.alt;
          lightboxTitle.textContent = img.dataset.title;
          lightboxDesc.textContent = img.dataset.description;
          lightbox.style.display = 'flex';
          document.body.style.overflow = 'hidden';
        }
      });
    });

    closeBtn.addEventListener('click', function () {
      lightbox.style.display = 'none';
      document.body.style.overflow = 'auto';
    });

    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
      }
    });
  }

  // Initialize everything when DOM is loaded
  document.addEventListener('DOMContentLoaded', function () {
    initMasonry();
    initDragging();
    initLightbox();

    // Recalculate masonry layout when images load
    window.addEventListener('load', initMasonry);
  });
</script>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>