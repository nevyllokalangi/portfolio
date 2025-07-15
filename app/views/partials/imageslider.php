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
    animation: slide 24s infinite;
  }

  .slider-img {
    object-fit: cover;
    filter: brightness(30%);
    min-width: 100%;
  }

  .slider-title span {
    position: absolute;
    color: var(--primary);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .slider-title span::before {
    content: "";
    animation: text 24s infinite;
  }

  @keyframes text {
    0% {
      opacity: 0;
      content: "D-SPORA 2022";
    }

    3.75% {
      opacity: 1;
    }

    12.5% {
      opacity: 1;
    }

    15% {
      opacity: 0;
      content: "D-SPORA 2022";
    }

    25% {
      opacity: 0;
      content: "D-SPORA 2022";
    }

    28.75% {
      opacity: 1;
    }

    37.5% {
      opacity: 1;
    }

    40% {
      opacity: 0;
      content: "D-SPORA 2022";
    }

    50% {
      opacity: 0;
      content: "Leadership Camp 2022";
    }

    53.75% {
      opacity: 1;
    }

    62.5% {
      opacity: 1;
    }

    65% {
      opacity: 0;
      content: "Leadership Camp 2022";
    }

    75% {
      opacity: 0;
      content: "Kidsstar 18th Birthday";
    }

    78.75% {
      opacity: 1;
    }

    87.5% {
      opacity: 1;
    }

    90% {
      opacity: 0;
    }

    100% {
      opacity: 0;
      content: "Kidsstar 18th Birthday";
    }
  }

  @keyframes slide {
    0% {
      transform: translate(0);
    }

    13% {
      transform: translate(0);
    }

    26% {
      transform: translate(-100%);
    }

    39% {
      transform: translate(-100%);
    }

    52% {
      transform: translate(-200%);
    }

    65% {
      transform: translate(-200%);
    }

    78% {
      transform: translate(-300%);
    }

    90% {
      transform: translate(-300%);
    }

    100% {
      transform: translate(0);
    }
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