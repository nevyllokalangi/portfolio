<?php
require_once __DIR__ . '/../../functions/helpers.php';
$settings = get_settings($pdo);
?>
<section class="portfolio">
  <!-- Interactive Folder Component -->
  <div class="folder" id="js_folder">
    <div class="folder-summary" id="js_toggle-folder">
      <div class="folder-summary__start">
        <button class="folder-collapse-button" id="js_folder-collapse-button">
          <svg id="js_folder-collapse-button-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="feather feather-chevron-up">
            <polyline points="18 15 12 9 6 15"></polyline>
          </svg>
        </button>
        <div class="folder-summary__file-count" id="js_folder-summary-amount">
          <span class="folder-summary__file-count__amount">2</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-folder">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
          </svg>
        </div>
      </div>
      <div class="folder-summary__details">
        <div class="folder-summary__details__name">
          My Portfolio
        </div>
        <div class="folder-summary__details__share">
          Contains
          <span class="shared-user">
            <span class="shared-user__name">2 files</span>
          </span>
        </div>
      </div>
    </div>

    <ul class="folder-content" id="js_folder-content">
      <?php if (!empty($settings['portfolio'])): ?>
        <li class="folder-item js_folder-item">
          <div class="folder-item__icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256"
              height="256" viewBox="0 0 256 256" xml:space="preserve">
              <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                <path
                  d="M 19.309 0 C 15.04 0 11.58 3.46 11.58 7.729 v 47.153 v 27.389 c 0 4.269 3.46 7.729 7.729 7.729 h 51.382 c 4.269 0 7.729 -3.46 7.729 -7.729 V 54.882 V 25.82 L 52.601 0 H 19.309 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(2,99,209); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 78.42 25.82 H 60.159 c -4.175 0 -7.559 -3.384 -7.559 -7.559 V 0 L 78.42 25.82 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(78,146,223); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path
                  d="M 45.544 64.099 c -3.416 0 -6.195 -2.778 -6.195 -6.194 v -7.915 c 0 -3.416 2.779 -6.195 6.195 -6.195 c 3.417 0 6.196 2.779 6.196 6.195 v 7.915 C 51.74 61.32 48.961 64.099 45.544 64.099 z M 45.544 47.294 c -1.486 0 -2.695 1.209 -2.695 2.695 v 7.915 c 0 1.485 1.209 2.694 2.695 2.694 c 1.487 0 2.696 -1.209 2.696 -2.694 v -7.915 C 48.24 48.503 47.031 47.294 45.544 47.294 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path
                  d="M 28.517 64.099 h -3.845 c -0.966 0 -1.75 -0.783 -1.75 -1.75 V 45.544 c 0 -0.966 0.784 -1.75 1.75 -1.75 h 3.845 c 3.747 0 6.795 3.048 6.795 6.795 v 6.715 C 35.312 61.051 32.264 64.099 28.517 64.099 z M 26.422 60.599 h 2.095 c 1.817 0 3.295 -1.479 3.295 -3.295 v -6.715 c 0 -1.816 -1.479 -3.295 -3.295 -3.295 h -2.095 V 60.599 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path
                  d="M 65.328 64.099 h -2.817 c -3.763 0 -6.823 -3.061 -6.823 -6.822 v -6.659 c 0 -3.763 3.061 -6.823 6.823 -6.823 h 2.817 c 0.967 0 1.75 0.784 1.75 1.75 c 0 0.967 -0.783 1.75 -1.75 1.75 h -2.817 c -1.832 0 -3.323 1.491 -3.323 3.323 v 6.659 c 0 1.832 1.491 3.322 3.323 3.322 h 2.817 c 0.967 0 1.75 0.783 1.75 1.75 S 66.295 64.099 65.328 64.099 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
              </g>
            </svg>
          </div>
          <div class="folder-item__details">
            Resume.docx
          </div>
          <div class="folder-item__size">
            <a href="<?= htmlspecialchars($settings['portfolio']) ?>" target="_blank">View</a>
          </div>
        </li>
      <?php endif; ?>
      <?php if (!empty($settings['portfolio2'])): ?>
        <li class="folder-item js_folder-item">
          <div class="folder-item__icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256"
              height="256" viewBox="0 0 256 256" xml:space="preserve">
              <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                <path
                  d="M 19.309 0 C 15.04 0 11.58 3.46 11.58 7.729 v 47.153 v 27.389 c 0 4.269 3.46 7.729 7.729 7.729 h 51.382 c 4.269 0 7.729 -3.46 7.729 -7.729 V 54.882 V 25.82 L 52.601 0 H 19.309 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(226,38,43); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 78.42 25.82 H 60.159 c -4.175 0 -7.559 -3.384 -7.559 -7.559 V 0 L 78.42 25.82 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(235,103,106); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path
                  d="M 30.116 46.949 h -5.944 c -0.966 0 -1.75 0.783 -1.75 1.75 v 9.854 v 6.748 c 0 0.967 0.784 1.75 1.75 1.75 s 1.75 -0.783 1.75 -1.75 v -4.998 h 4.194 c 2.53 0 4.588 -2.059 4.588 -4.588 v -4.177 C 34.704 49.008 32.646 46.949 30.116 46.949 z M 31.204 55.715 c 0 0.6 -0.488 1.088 -1.088 1.088 h -4.194 v -6.354 h 4.194 c 0.6 0 1.088 0.488 1.088 1.089 V 55.715 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path
                  d="M 43.703 46.949 h -3.246 c -0.966 0 -1.75 0.783 -1.75 1.75 v 16.602 c 0 0.967 0.784 1.75 1.75 1.75 h 3.246 c 4.018 0 7.286 -3.269 7.286 -7.287 v -5.527 C 50.989 50.218 47.721 46.949 43.703 46.949 z M 47.489 59.764 c 0 2.088 -1.698 3.787 -3.786 3.787 h -1.496 V 50.449 h 1.496 c 2.088 0 3.786 1.699 3.786 3.787 V 59.764 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path
                  d="M 65.828 46.949 h -8.782 c -0.967 0 -1.75 0.783 -1.75 1.75 v 16.602 c 0 0.967 0.783 1.75 1.75 1.75 s 1.75 -0.783 1.75 -1.75 V 58.75 h 4.001 c 0.967 0 1.75 -0.783 1.75 -1.75 s -0.783 -1.75 -1.75 -1.75 h -4.001 v -4.801 h 7.032 c 0.967 0 1.75 -0.783 1.75 -1.75 S 66.795 46.949 65.828 46.949 z"
                  style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                  transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
              </g>
            </svg>
          </div>
          <div class="folder-item__details">
            Resume.pdf
          </div>
          <div class="folder-item__size">
            <a href="<?= htmlspecialchars($settings['portfolio2']) ?>" target="_blank">View</a>

          </div>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</section>

<style>
  /* Folder Styles */
  .portfolio {
    width: 100%;
    padding: 0;
  }

  .folder {
    background: var(--color-bg-secondary);
    border-radius: 10px;
    overflow: hidden;
    width: 100%;
  }

  /* Shared User Styles */
  .shared-user {
    align-items: center;
    color: var(--color-accent-primary);
    display: inline-flex;
    font-weight: 500;
    margin-left: 5px;
    outline: none;
    text-decoration: none;
  }

  .shared-user__avatar {
    width: 15px;
    height: 15px;
    margin-right: 3px;
  }

  /* Folder Summary Styles */
  .folder-summary {
    padding: 15px 20px 15px 15px;
    cursor: pointer;
    display: flex;
    line-height: 1;
    height: 80px;
    position: relative;
    align-items: center;
  }

  .folder-summary__start {
    position: relative;
  }

  .folder-summary__file-count {
    position: absolute;
    top: -3px;
  }

  .folder-summary__file-count__amount {
    color: var(--color-text-primary);
    font-size: 12px;
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
  }

  .folder-summary__file-count svg {
    color: var(--color-accent-primary);
    height: 30px;
    width: auto;
  }

  .folder-summary__details {
    padding: 2px 0 0 13px;
  }

  .folder-summary__details__name font-size: 20px;
  font-weight: 500;
  }

  .folder-summary__details__share {
    align-items: center;
    display: flex;
    font-size: 15px;
    margin-top: 8px;
  }

  .folder-summary::after {
    background: var(--color-bg-surface);
    bottom: -2px;
    content: "";
    height: 2px;
    left: 0;
    position: absolute;
    right: 0;
  }

  /* Chevron Button Styles */
  .folder-collapse-button {
    appearance: none;
    background: transparent;
    border-radius: 30px;
    border: 0;
    cursor: pointer;
    height: 30px;
    opacity: 0;
    outline: none;
    position: absolute;
    position: relative;
    width: 30px;
    z-index: 1;
  }

  .folder-collapse-button::after {
    background: var(--color-bg-surface);
    border-radius: 40px;
    content: "";
    height: 35px;
    left: 50%;
    opacity: 0;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.2s ease-out;
    width: 35px;
  }

  .folder-collapse-button:hover::after {
    opacity: 1;
  }

  .folder-collapse-button svg {
    color: #9c9c9e;
    left: 50%;
    position: absolute;
    stroke-width: 3;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
  }

  /* Folder Item Styles */
  .folder-item {
    align-items: center;
    display: flex;
    height: 80px;
    line-height: 1;
    opacity: 0;
    padding: 20px 25px;
    position: relative;
  }

  .folder-item__icon svg {
    display: block;
    height: 40px;
    width: auto;
  }

  .folder-item__details {
    padding: 1px 0 0 15px;
  }

  .folder-item__details__name svg {
    height: auto;
    position: absolute;
    top: 50%;
    transform: translate(5px, calc(-50% + 1px));
    width: 20px;
  }

  .folder-item__size {
    margin-left: auto;
  }

  .folder-item__size a {
    display: inline-block;
    background: var(--color-accent-primary);
    color: var(--textPrimary);
    padding: 6px 18px;
    border-radius: 5px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s;
  }

  .folder-item__size a:hover {
    background: var(--color-accent-hover);
  }

  /* Folder Content Styles */
  .folder-content {
    height: 0;
    list-style: none;
    margin: 0;
    overflow: hidden;
    padding: 0;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script>
  const toggleFolder = document.getElementById("js_toggle-folder");

  // Animation
  const showFolderContentAnimation = anime.timeline({
    easing: "easeOutCubic",
    autoplay: false
  });

  showFolderContentAnimation
    .add({
      targets: "#js_folder-content",
      height: [0, 160],
      duration: 350
    })
    .add(
      {
        targets: "#js_folder-summary-amount",
        opacity: [1, 0],
        duration: 400
      },
      "-=350"
    )
    .add(
      {
        targets: "#js_folder-collapse-button",
        opacity: [0, 1],
        duration: 400
      },
      "-=300"
    )
    .add(
      {
        targets: "#js_folder-collapse-button-icon",
        duration: 300,
        translateX: ["-50%", "-50%"],
        translateY: ["-50%", "-50%"],
        rotate: ["0deg", "180deg"]
      },
      "-=400"
    )
    .add(
      {
        targets: ".js_folder-item",
        translateY: [20, 0],
        opacity: [0, 1],
        duration: 300,
        delay: (el, i, l) => i * 120
      },
      "-=275"
    );

  // Trigger
  toggleFolder.addEventListener("click", () => {
    if (showFolderContentAnimation.began) {
      showFolderContentAnimation.reverse();
      if (
        showFolderContentAnimation.progress === 100 &&
        showFolderContentAnimation.direction === "reverse"
      ) {
        showFolderContentAnimation.completed = false;
      }
    }

    if (showFolderContentAnimation.paused) {
      showFolderContentAnimation.play();
    }
  });
</script>