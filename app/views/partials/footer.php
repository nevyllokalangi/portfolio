<?php
require_once __DIR__ . '/../../functions/helpers.php';
$settings = get_settings($pdo);
?>
<style type="text/css" scoped>
  /* Footer Base Styles */
  footer {
    position: fixed;
    align-self: center;
    bottom: 0;
    width: 100%;
    height: 400px;
    max-width: 1920px;
    background-color: var(--primary);
    color: var(--primaryFont);
    font-size: 16px;
    z-index: 1;
    display: flex;
    flex-direction: column;
    border-top: 1px solid var(--borderColor);
  }

  .footer-nav {
    height: 100%;
    width: 100%;
    padding: 1rem 10vw;
    box-sizing: border-box;
  }

  /* Main Footer Content */
  .footer-main {
    height: 100%;
    padding: 3rem 0;
  }

  .footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 3rem;
  }

  .footer-section {
    flex: auto;
    min-width: 200px;
  }

  .footer-subtitle {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--tertiaryFont);
  }

  .footer-links {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }

  .footer-link {
    text-decoration: none;
    color: var(--primaryFont);
    font-size: 1.125rem;
    transition: color 0.3s ease;
  }

  .footer-link:hover {
    color: var(--hoverColor);
  }

  .footer-social-links {
    display: flex;
    flex-direction: row;
    flex: 3;
    gap: 1rem;
    margin-top: 1rem;
  }

  .footer-social-button {
    width: 50px;
    height: 50px;
  }

  /* Copyright Section */
  .footer-row3 {
    display: flex;
    justify-content: space-between;
    width: 100%;
    padding: 1rem 10%;
    background-color: var(--primary);
  }

  .footer-cr {
    color: var(--textSecondary);
    font-size: 0.875rem;
  }

  /* Responsive Design */
  @media (max-width: 1024px) {
    .footer-nav {
      padding: 2rem 5%;
    }

    .footer-content {
      gap: 2rem;
    }

    .footer-title {
      font-size: 1.75rem;
    }
  }

  @media (max-width: 768px) {
    .footer-row1 {
      flex-direction: column;
      gap: 2rem;
    }

    .footer-content {
      flex-direction: column;
      gap: 3rem;
    }

    .footer-section {
      min-width: 100%;
    }

    .footer-row3 {
      flex-direction: column;
      gap: 0.5rem;
      align-items: center;
      text-align: center;
    }
  }
</style>

<footer id="footer">
  <div class="footer-nav">
    <div class="footer-main">
      <div class="footer-content">
        <div class="footer-section">
          <!-- Portfolio Download Section -->
          <?php include __DIR__ . '/../partials/portfolio.php'; ?>
        </div>

        <div class="footer-section">
          <h2 class="footer-subtitle">Explore</h2>
          <div class="footer-links">
            <a href="/" class="footer-link">Home</a>
            <a href="/about" class="footer-link">About Me</a>
            <a href="/contact" class="footer-link">Contact</a>
            <a href="/project" class="footer-link">Project</a>
            <a href="/blog" class="footer-link">Blog</a>
          </div>
        </div>

        <div class="footer-section">
          <h2 class="footer-subtitle">Follow Me</h2>
          <div class="footer-social-links">
            <a class="footer-social-button" href="<?= htmlspecialchars($settings['instagram']) ?>" target="_blank"
              aria-label="Instagram">
              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                <radialGradient id="yOrnnhliCrdS2gy~4tD8ma_Xy10Jcu1L2Su_gr1" cx="19.38" cy="42.035" r="44.899"
                  gradientUnits="userSpaceOnUse">
                  <stop offset="0" stop-color="#fd5"></stop>
                  <stop offset=".328" stop-color="#ff543f"></stop>
                  <stop offset=".348" stop-color="#fc5245"></stop>
                  <stop offset=".504" stop-color="#e64771"></stop>
                  <stop offset=".643" stop-color="#d53e91"></stop>
                  <stop offset=".761" stop-color="#cc39a4"></stop>
                  <stop offset=".841" stop-color="#c837ab"></stop>
                </radialGradient>
                <path fill="url(#yOrnnhliCrdS2gy~4tD8ma_Xy10Jcu1L2Su_gr1)"
                  d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z">
                </path>
                <radialGradient id="yOrnnhliCrdS2gy~4tD8mb_Xy10Jcu1L2Su_gr2" cx="11.786" cy="5.54" r="29.813"
                  gradientTransform="matrix(1 0 0 .6663 0 1.849)" gradientUnits="userSpaceOnUse">
                  <stop offset="0" stop-color="#4168c9"></stop>
                  <stop offset=".999" stop-color="#4168c9" stop-opacity="0"></stop>
                </radialGradient>
                <path fill="url(#yOrnnhliCrdS2gy~4tD8mb_Xy10Jcu1L2Su_gr2)"
                  d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z">
                </path>
                <path fill="#fff"
                  d="M24,31c-3.859,0-7-3.14-7-7s3.141-7,7-7s7,3.14,7,7S27.859,31,24,31z M24,19c-2.757,0-5,2.243-5,5	s2.243,5,5,5s5-2.243,5-5S26.757,19,24,19z">
                </path>
                <circle cx="31.5" cy="16.5" r="1.5" fill="#fff"></circle>
                <path fill="#fff"
                  d="M30,37H18c-3.859,0-7-3.14-7-7V18c0-3.86,3.141-7,7-7h12c3.859,0,7,3.14,7,7v12	C37,33.86,33.859,37,30,37z M18,13c-2.757,0-5,2.243-5,5v12c0,2.757,2.243,5,5,5h12c2.757,0,5-2.243,5-5V18c0-2.757-2.243-5-5-5H18z">
                </path>
              </svg>
            </a>
            <a class="footer-social-button" href="<?= htmlspecialchars($settings['linkedin']) ?>" target="_blank"
              aria-label="Linkedin">
              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                <path fill="#0078d4"
                  d="M42,37c0,2.762-2.238,5-5,5H11c-2.761,0-5-2.238-5-5V11c0-2.762,2.239-5,5-5h26c2.762,0,5,2.238,5,5	V37z">
                </path>
                <path
                  d="M30,37V26.901c0-1.689-0.819-2.698-2.192-2.698c-0.815,0-1.414,0.459-1.779,1.364	c-0.017,0.064-0.041,0.325-0.031,1.114L26,37h-7V18h7v1.061C27.022,18.356,28.275,18,29.738,18c4.547,0,7.261,3.093,7.261,8.274	L37,37H30z M11,37V18h3.457C12.454,18,11,16.528,11,14.499C11,12.472,12.478,11,14.514,11c2.012,0,3.445,1.431,3.486,3.479	C18,16.523,16.521,18,14.485,18H18v19H11z"
                  opacity=".05"></path>
                <path
                  d="M30.5,36.5v-9.599c0-1.973-1.031-3.198-2.692-3.198c-1.295,0-1.935,0.912-2.243,1.677	c-0.082,0.199-0.071,0.989-0.067,1.326L25.5,36.5h-6v-18h6v1.638c0.795-0.823,2.075-1.638,4.238-1.638	c4.233,0,6.761,2.906,6.761,7.774L36.5,36.5H30.5z M11.5,36.5v-18h6v18H11.5z M14.457,17.5c-1.713,0-2.957-1.262-2.957-3.001	c0-1.738,1.268-2.999,3.014-2.999c1.724,0,2.951,1.229,2.986,2.989c0,1.749-1.268,3.011-3.015,3.011H14.457z"
                  opacity=".07"></path>
                <path fill="#fff"
                  d="M12,19h5v17h-5V19z M14.485,17h-0.028C12.965,17,12,15.888,12,14.499C12,13.08,12.995,12,14.514,12	c1.521,0,2.458,1.08,2.486,2.499C17,15.887,16.035,17,14.485,17z M36,36h-5v-9.099c0-2.198-1.225-3.698-3.192-3.698	c-1.501,0-2.313,1.012-2.707,1.99C24.957,25.543,25,26.511,25,27v9h-5V19h5v2.616C25.721,20.5,26.85,19,29.738,19	c3.578,0,6.261,2.25,6.261,7.274L36,36L36,36z">
                </path>
              </svg>
            </a>
            <a class="footer-social-button" href="<?= htmlspecialchars($settings['tiktok']) ?>" target="_blank"
              aria-label="Tiktok">
              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                <linearGradient id="dYJkfAQNfP2dCzgdw4ruIa_fdfLpA6fsXN2_gr1" x1="23.672" x2="23.672" y1="6.365"
                  y2="42.252" gradientTransform="translate(.305 -.206)" gradientUnits="userSpaceOnUse">
                  <stop offset="0" stop-color="#4c4c4c"></stop>
                  <stop offset="1" stop-color="#343434"></stop>
                </linearGradient>
                <path fill="url(#dYJkfAQNfP2dCzgdw4ruIa_fdfLpA6fsXN2_gr1)"
                  d="M40.004,41.969L8.031,42c-1.099,0.001-1.999-0.897-2-1.996L6,8.031	c-0.001-1.099,0.897-1.999,1.996-2L39.969,6c1.099-0.001,1.999,0.897,2,1.996L42,39.969C42.001,41.068,41.103,41.968,40.004,41.969z">
                </path>
                <path fill="#ec407a" fill-rule="evenodd"
                  d="M29.208,20.607c1.576,1.126,3.507,1.788,5.592,1.788v-4.011	c-0.395,0-0.788-0.041-1.174-0.123v3.157c-2.085,0-4.015-0.663-5.592-1.788v8.184c0,4.094-3.321,7.413-7.417,7.413	c-1.528,0-2.949-0.462-4.129-1.254c1.347,1.376,3.225,2.23,5.303,2.23c4.096,0,7.417-3.319,7.417-7.413V20.607L29.208,20.607z M30.657,16.561c-0.805-0.879-1.334-2.016-1.449-3.273v-0.516h-1.113C28.375,14.369,29.331,15.734,30.657,16.561L30.657,16.561z M19.079,30.832c-0.45-0.59-0.693-1.311-0.692-2.053c0-1.873,1.519-3.391,3.393-3.391c0.349,0,0.696,0.053,1.029,0.159v-4.1	c-0.389-0.053-0.781-0.076-1.174-0.068v3.191c-0.333-0.106-0.68-0.159-1.03-0.159c-1.874,0-3.393,1.518-3.393,3.391	C17.213,29.127,17.972,30.274,19.079,30.832z"
                  clip-rule="evenodd"></path>
                <path fill="#fff" fill-rule="evenodd"
                  d="M28.034,19.63c1.576,1.126,3.507,1.788,5.592,1.788v-3.157	c-1.164-0.248-2.194-0.856-2.969-1.701c-1.326-0.827-2.281-2.191-2.561-3.788h-2.923V28.79c-0.007,1.867-1.523,3.379-3.393,3.379	c-1.102,0-2.081-0.525-2.701-1.338c-1.107-0.558-1.866-1.705-1.866-3.029c0-1.873,1.519-3.391,3.393-3.391	c0.359,0,0.705,0.056,1.03,0.159v-3.19c-4.024,0.083-7.26,3.369-7.26,7.411c0,2.018,0.806,3.847,2.114,5.183	c1.18,0.792,2.601,1.254,4.129,1.254c4.096,0,7.417-3.319,7.417-7.413L28.034,19.63L28.034,19.63z"
                  clip-rule="evenodd"></path>
                <path fill="#81d4fa" fill-rule="evenodd"
                  d="M33.626,18.262v-0.854c-1.05,0.002-2.078-0.292-2.969-0.848	C31.445,17.423,32.483,18.018,33.626,18.262z M28.095,12.772c-0.027-0.153-0.047-0.306-0.061-0.461v-0.516h-4.036v16.019	c-0.006,1.867-1.523,3.379-3.393,3.379c-0.549,0-1.067-0.13-1.526-0.362c0.62,0.813,1.599,1.338,2.701,1.338	c1.87,0,3.386-1.512,3.393-3.379V12.772H28.095z M21.635,21.38v-0.909c-0.337-0.046-0.677-0.069-1.018-0.069	c-4.097,0-7.417,3.319-7.417,7.413c0,2.567,1.305,4.829,3.288,6.159c-1.308-1.336-2.114-3.165-2.114-5.183	C14.374,24.749,17.611,21.463,21.635,21.38z"
                  clip-rule="evenodd"></path>
              </svg>
            </a>
            <a class="footer-social-button" href="<?= htmlspecialchars($settings['youtube']) ?>" target="_blank"
              aria-label="Youtube">
              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                <linearGradient id="PgB_UHa29h0TpFV_moJI9a_9a46bTk3awwI_gr1" x1="9.816" x2="41.246" y1="9.871"
                  y2="41.301" gradientUnits="userSpaceOnUse">
                  <stop offset="0" stop-color="#f44f5a"></stop>
                  <stop offset=".443" stop-color="#ee3d4a"></stop>
                  <stop offset="1" stop-color="#e52030"></stop>
                </linearGradient>
                <path fill="url(#PgB_UHa29h0TpFV_moJI9a_9a46bTk3awwI_gr1)"
                  d="M45.012,34.56c-0.439,2.24-2.304,3.947-4.608,4.267C36.783,39.36,30.748,40,23.945,40	c-6.693,0-12.728-0.64-16.459-1.173c-2.304-0.32-4.17-2.027-4.608-4.267C2.439,32.107,2,28.48,2,24s0.439-8.107,0.878-10.56	c0.439-2.24,2.304-3.947,4.608-4.267C11.107,8.64,17.142,8,23.945,8s12.728,0.64,16.459,1.173c2.304,0.32,4.17,2.027,4.608,4.267	C45.451,15.893,46,19.52,46,24C45.89,28.48,45.451,32.107,45.012,34.56z">
                </path>
                <path
                  d="M32.352,22.44l-11.436-7.624c-0.577-0.385-1.314-0.421-1.925-0.093C18.38,15.05,18,15.683,18,16.376	v15.248c0,0.693,0.38,1.327,0.991,1.654c0.278,0.149,0.581,0.222,0.884,0.222c0.364,0,0.726-0.106,1.04-0.315l11.436-7.624	c0.523-0.349,0.835-0.932,0.835-1.56C33.187,23.372,32.874,22.789,32.352,22.44z"
                  opacity=".05"></path>
                <path
                  d="M20.681,15.237l10.79,7.194c0.689,0.495,1.153,0.938,1.153,1.513c0,0.575-0.224,0.976-0.715,1.334	c-0.371,0.27-11.045,7.364-11.045,7.364c-0.901,0.604-2.364,0.476-2.364-1.499V16.744C18.5,14.739,20.084,14.839,20.681,15.237z"
                  opacity=".07"></path>
                <path fill="#fff"
                  d="M19,31.568V16.433c0-0.743,0.828-1.187,1.447-0.774l11.352,7.568c0.553,0.368,0.553,1.18,0,1.549	l-11.352,7.568C19.828,32.755,19,32.312,19,31.568z">
                </path>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-row3">
    <a class="footer-cr">Nevyllo Zamuel Kalangi © <span id="year"></span> - Privacy Policy</a>
    <a class="footer-cr"><?= htmlspecialchars($settings['location']) ?></a>
  </div>
</footer>

<script>
  document.getElementById("year").innerHTML = new Date().getFullYear();
</script>