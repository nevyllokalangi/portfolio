<section class="scrolling-image-section">
  <!-- Top row scrolling left -->
  <div class="scrolling-image-row top-row">
    <div class="scrolling-image-track">
      <?php for ($i = 0; $i < 8; $i++): ?>
        <img class="scrolling-image" src="/public/img/hl1.jpg" alt="Kidsstar 18th Birthday" draggable="false"
          loading="lazy" oncontextmenu="return false;" />
      <?php endfor; ?>
    </div>
  </div>

  <!-- Bottom row scrolling right -->
  <div class="scrolling-image-row bottom-row">
    <div class="scrolling-image-track">
      <?php for ($i = 0; $i < 8; $i++): ?>
        <img class="scrolling-image" src="/public/img/hl1.jpg" alt="Kidsstar 18th Birthday" draggable="false"
          loading="lazy" oncontextmenu="return false;" />
      <?php endfor; ?>
    </div>
  </div>

  <div class="scrolling-btn-container">
    <a href="/about" class="scrolling-btn">
      <span class="scrolling-btn-circle"></span>
      <span class="scrolling-btn-white-circle">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 12">
          <path d="M17.104 5.072l-4.138-4.014L14.056 0l6 5.82-6 5.82-1.09-1.057 4.138-4.014H0V5.072h17.104z"></path>
        </svg>
      </span>
      <span class="scrolling-btn-text">DISCOVER MY BIOGRAPHY</span>
    </a>
  </div>
</section>

<style>
  .scrolling-image-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 40px;
    margin: 0 auto;
    overflow: hidden;
    width: 100%;
    padding: 40px 0;
  }

  .scrolling-image-row {
    height: 250px;
    width: 100%;
    overflow: hidden;
    position: relative;
  }

  .scrolling-image-track {
    display: flex;
    gap: 20px;
    height: 100%;
    align-items: center;
    position: absolute;
    white-space: nowrap;
  }

  .scrolling-image {
    border-radius: 12px;
    height: 100%;
    width: auto;
    object-fit: cover;
    flex-shrink: 0;
  }

  .scrolling-image:hover {
    filter: brightness(60%);
    transition: filter 0.3s ease;
  }

  /* Top row animation (left scroll) */
  .top-row .scrolling-image-track {
    animation: scrollLeft 10s linear infinite;
  }

  /* Bottom row animation (right scroll) */
  .bottom-row .scrolling-image-track {
    animation: scrollRight 10s linear infinite;
  }

  @keyframes scrollLeft {
    0% {
      transform: translateX(0);
    }

    100% {
      transform: translateX(-50%);
    }
  }

  @keyframes scrollRight {
    0% {
      transform: translateX(-50%);
    }

    100% {
      transform: translateX(0);
    }
  }

  /* Button Styles */
  .scrolling-btn-container {
    display: flex;
    justify-content: center;
    width: 100%;
    padding-top: 20px;
  }

  .scrolling-btn {
    display: inline-flex;
    position: relative;
    text-align: center;
    color: #ffffff;
    padding: 24px 64px 32px 64px;
    letter-spacing: 1px;
  }

  .scrolling-btn-circle,
  .scrolling-btn-text,
  .scrolling-btn-white-circle {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
  }

  .scrolling-btn-circle {
    left: 0;
    width: 55px;
    height: 55px;
    border-radius: 50px;
    box-shadow: 0 0 0 1px #fff;
    transition: transform 0.3s linear;
  }

  .scrolling-btn-white-circle {
    left: 30px;
    transform: translate(-50%, -50%) scale(0);
    width: 56px;
    height: 56px;
    border-radius: 100%;
    background: #ffffff;
    display: flex;
    transition: transform 0.3s ease-in-out;
    z-index: 2;
  }

  .scrolling-btn-white-circle svg {
    width: 24px;
    height: 24px;
    margin: auto;
  }

  .scrolling-btn-text {
    left: 15%;
    padding: 0 8px;
    transition: transform 0.3s linear;
  }

  .scrolling-btn:hover .scrolling-btn-circle {
    transform: translateY(-50%) scale(0);
  }

  .scrolling-btn:hover .scrolling-btn-white-circle {
    transform: translate(-50%, -50%) scale(1);
  }

  .scrolling-btn:hover .scrolling-btn-text {
    transform: translate(60px, -50%);
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Pause animation on hover
    const gallery = document.querySelector('.scrolling-image-container');
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