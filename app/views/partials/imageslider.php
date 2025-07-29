<section class="slider-section">
  <div class="slider">
    <img class="slider-img" src="/public/img/hl1.jpg" alt="D-SPORA 2022" draggable="false" loading="lazy"
      oncontextmenu="return false;" />
    <img class="slider-img" src="/public/img/hl1.jpg" alt="D-SPORA 2022" draggable="false" loading="lazy"
      oncontextmenu="return false;" />
    <img class="slider-img" src="/public/img/hl1.jpg" alt="Leadership Camp 2022" draggable="false" loading="lazy"
      oncontextmenu="return false;" />
    <img class="slider-img" src="/public/img/hl1.jpg" alt="Kidsstar 18th Birthday" draggable="false" loading="lazy"
      oncontextmenu="return false;" />
  </div>
  <div class="slider-title">
    <span></span>
  </div>
  <button class="slider-arrow left" aria-label="Previous slide">&#10094;</button>
  <button class="slider-arrow right" aria-label="Next slide">&#10095;</button>
  <div class="slider-dots"></div>
</section>
<style>
  /* Highlight */
  .slider-section {
    position: relative;
    overflow: hidden;
    text-align: center;
    font-size: 6vh;
    height: 48vh;
    width: 100%;
    border-radius: 12px;
    animation: ambient 24s infinite;
  }

  .slider {
    display: flex;
    height: 100%;
    transition: transform 0.7s cubic-bezier(0.77, 0, 0.175, 1);
  }

  .slider-img {
    object-fit: cover;
    filter: brightness(30%);
    min-width: 100%;
  }

  .slider-title span {
    position: absolute;
    color: var(--primary-color);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
    font-size: 1em;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.2em 1em;
    border-radius: 8px;
    opacity: 1;
    transition: opacity 0.5s;
  }

  .slider-title span.fade {
    opacity: 0;
    transition: opacity 0.5s;
  }

  .slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    color: #fff;
    font-size: 2.5rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
    z-index: 2;
    user-select: none;
    background: transparent;
  }

  .slider-arrow.left {
    left: 16px;
  }

  .slider-arrow.right {
    right: 16px;
  }

  .slider-arrow:hover {
    color: #cecece;
  }

  .slider-dots {
    position: absolute;
    bottom: 18px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 2;
  }

  .slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--primaryFont);
    cursor: pointer;
    transition: background 0.2s, border 0.2s;
  }

  .slider-dot.active {
    background: var(--tertiaryFont);
  }

  /* Highlight */
  /* Responsive CSS */
  @media (max-width: 800px) {
    .slider-section {
      height: 40vw;
    }

    .slider-title span {
      font-size: 20px;
    }
  }

  /* Responsive CSS */
</style>
<script>
  // Slider logic
  (function () {
    const images = Array.from(document.querySelectorAll('.slider-img'));
    const titles = [
      'D-SPORA 2022',
      'D-SPORA 2022',
      'Leadership Camp 2022',
      'Kidsstar 18th Birthday'
    ];
    const slider = document.querySelector('.slider');
    const titleSpan = document.querySelector('.slider-title span');
    const dotsContainer = document.querySelector('.slider-dots');
    const leftArrow = document.querySelector('.slider-arrow.left');
    const rightArrow = document.querySelector('.slider-arrow.right');
    let current = 0;
    let interval = null;

    // Create dots
    titles.forEach((_, idx) => {
      const dot = document.createElement('div');
      dot.className = 'slider-dot' + (idx === 0 ? ' active' : '');
      dot.setAttribute('tabindex', '0');
      dot.setAttribute('aria-label', `Go to slide ${idx + 1}`);
      dot.addEventListener('click', () => goTo(idx));
      dot.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') goTo(idx);
      });
      dotsContainer.appendChild(dot);
    });
    const dots = Array.from(document.querySelectorAll('.slider-dot'));

    function updateSlider() {
      slider.style.transform = `translate(-${current * 100}%)`;
      dots.forEach((dot, i) => dot.classList.toggle('active', i === current));
      if (titleSpan) {
        titleSpan.classList.add('fade');
        setTimeout(() => {
          titleSpan.textContent = titles[current];
          titleSpan.classList.remove('fade');
        }, 250);
      }
    }

    function goTo(idx) {
      current = idx;
      updateSlider();
      resetInterval();
    }

    function prev() {
      current = (current - 1 + images.length) % images.length;
      updateSlider();
      resetInterval();
    }
    function next() {
      current = (current + 1) % images.length;
      updateSlider();
      resetInterval();
    }
    leftArrow.addEventListener('click', prev);
    rightArrow.addEventListener('click', next);
    leftArrow.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') prev(); });
    rightArrow.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') next(); });

    function resetInterval() {
      if (interval) clearInterval(interval);
      interval = setInterval(next, 6000);
    }
    updateSlider();
    resetInterval();
    // Pause on hover
    slider.parentElement.addEventListener('mouseenter', () => clearInterval(interval));
    slider.parentElement.addEventListener('mouseleave', resetInterval);
  })();
</script>