<section>
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
          My Documents
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
      <li class="folder-item js_folder-item">
        <div class="folder-item__icon">
          <svg width="50" height="70" viewBox="0 0 50 70" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
              d="M33 0H5a5 5 0 00-5 5v60a5 5 0 005 5h40a5 5 0 005-5V17L33 0z" fill="#5085E8" />
            <path d="M50 29L35 16l15 .867V29z" fill="url(#paint0_linear)" opacity=".1" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M33 0l17 17H38a5 5 0 01-5-5V0z" fill="#A4BEF6" />
            <path fill="#fff" fill-opacity=".75" d="M13 39h24v3H13zM13 57h17v3H13zM13 51h24v3H13zM13 45h24v3H13z" />
            <defs>
              <linearGradient id="paint0_linear" x1="42.5" y1="16" x2="42.5" y2="29" gradientUnits="userSpaceOnUse">
                <stop />
                <stop offset="1" stop-opacity="0" />
              </linearGradient>
            </defs>
          </svg>
        </div>
        <div class="folder-item__details">
          Resume.docx
        </div>
        <div class="folder-item__size">
          <a href="https://docs.google.com/document/d/19F1zrf3Lsz3rQhKkBlk1XyizpMkBxE_D/edit?usp=drive_link&ouid=106321179050909623281&rtpof=true&sd=true"
            target="_blank">View</a>
        </div>
      </li>

      <li class="folder-item js_folder-item">
        <div class="folder-item__icon">
          <svg width="50" height="70" viewBox="0 0 50 70" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
              d="M33 0H5a5 5 0 00-5 5v60a5 5 0 005 5h40a5 5 0 005-5V17L33 0z" fill="#E8B52C" />
            <path d="M50 29L35 16l15 .867V29z" fill="url(#paint0_linear)" opacity=".1" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M33 0l17 17H38a5 5 0 01-5-5V0z" fill="#EEDA86" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M34 39H13v21h24V39h-3zM16 54.75h18v-10.5H16v10.5z"
              fill="#fff" fill-opacity=".75" />
            <defs>
              <linearGradient id="paint0_linear" x1="42.5" y1="16" x2="42.5" y2="29" gradientUnits="userSpaceOnUse">
                <stop />
                <stop offset="1" stop-opacity="0" />
              </linearGradient>
            </defs>
          </svg>
        </div>
        <div class="folder-item__details">
          Resume.pdf
        </div>
        <div class="folder-item__size">
          <a href="https://drive.google.com/file/d/1YY2EIbjbbHC1uIEIo49qgex0E-hPlRov/view?usp=drive_link"
            target="_blank">View</a>
        </div>
      </li>
    </ul>
  </div>
</section>

<style>
  /* Folder Styles */
  .folder {
    justify-self: center;
    background: var(--secondary);
    border-radius: 10px;
    overflow: hidden;
    width: 100%;
    max-width: 450px;
  }

  /* Shared User Styles */
  .shared-user {
    align-items: center;
    color: var(--textSecondary);
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
  }

  .folder-summary__start {
    position: relative;
  }

  .folder-summary__file-count {
    position: absolute;
    top: -3px;
  }

  .folder-summary__file-count__amount {
    color: var(--textPrimary);
    font-size: 12px;
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
  }

  .folder-summary__file-count svg {
    color: var(--tertiaryFont);
    height: 30px;
    width: auto;
  }

  .folder-summary__details {
    padding: 2px 0 0 13px;
  }

  .folder-summary__details__name {
    color: var(--textPrimary);
    font-size: 20px;
    font-weight: 500;
  }

  .folder-summary__details__share {
    align-items: center;
    color: var(--textSecondary);
    display: flex;
    font-size: 15px;
    margin-top: 8px;
  }

  .folder-summary::after {
    background: var(--borderColor);
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
    background: var(--secondary);
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
    align-items: flex-start;
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
    background: var(--tertiaryFont);
    color: var(--textPrimary);
    padding: 6px 18px;
    border-radius: 5px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s;
  }

  .folder-item__size a:hover {
    background: rgb(168, 21, 53);
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