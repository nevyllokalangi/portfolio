<?php
// Include functions if not already included
if (!function_exists('is_logged_in')) {
  require_once __DIR__ . '\..\..\..\database\config.php';
}
require_once __DIR__ . '/../../functions/helpers.php';
$settings = get_settings($pdo);
?>
<header id="nav">
  <div class="nav-container">
    <!-- Logo -->
    <div class="logo">
      <a href="/home" aria-label="Home">
        <img src="/public/img/Logo.png" draggable="false" height="50" alt="Company Logo" />
      </a>
    </div>

    <!-- Desktop Navigation -->
    <nav class="desktop-nav">
      <ul>
        <li><a href="/home" class="nav-link">HOME</a></li>
        <li><a href="/about" class="nav-link">ABOUT</a></li>
        <li><a href="/project" class="nav-link">PROJECT</a></li>
        <li><a href="/contact" class="nav-link">CONTACT</a></li>
      </ul>
    </nav>

    <!-- Right Side Controls -->
    <div class="nav-controls">
      <!-- Social Links -->
      <a class="social-button" href="<?= htmlspecialchars($settings['instagram']) ?>" target="_blank"
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
      <a class="social-button" href="<?= htmlspecialchars($settings['linkedin']) ?>" target="_blank"
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
      <a class="social-button" href="<?= htmlspecialchars($settings['tiktok']) ?>" target="_blank" aria-label="Tiktok">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
          <linearGradient id="dYJkfAQNfP2dCzgdw4ruIa_fdfLpA6fsXN2_gr1" x1="23.672" x2="23.672" y1="6.365" y2="42.252"
            gradientTransform="translate(.305 -.206)" gradientUnits="userSpaceOnUse">
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
      <a class="social-button" href="<?= htmlspecialchars($settings['youtube']) ?>" target="_blank"
        aria-label="Youtube">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
          <linearGradient id="PgB_UHa29h0TpFV_moJI9a_9a46bTk3awwI_gr1" x1="9.816" x2="41.246" y1="9.871" y2="41.301"
            gradientUnits="userSpaceOnUse">
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
      <!-- Share Button -->
      <button class="share-btn" aria-label="Share this page">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0,0,256,256">
          <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
            stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none"
            font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
            <g transform="scale(5.33333,5.33333)">
              <path
                d="M36,5c-3.84823,0 -7,3.15178 -7,7c0,0.58577 0.19854,1.10946 0.33594,1.6543l-11.99023,5.99805c-1.28658,-1.57841 -3.1642,-2.65234 -5.3457,-2.65234c-3.84823,0 -7,3.15178 -7,7c0,3.84822 3.15177,7 7,7c2.1815,0 4.05912,-1.07394 5.3457,-2.65234l11.99023,5.99805c-0.13739,0.54483 -0.33594,1.06853 -0.33594,1.6543c0,3.84822 3.15177,7 7,7c3.84823,0 7,-3.15178 7,-7c0,-3.84822 -3.15177,-7 -7,-7c-2.1815,0 -4.05912,1.07394 -5.3457,2.65234l-11.99023,-5.99805c0.13739,-0.54483 0.33594,-1.06853 0.33594,-1.6543c0,-0.58577 -0.19854,-1.10946 -0.33594,-1.6543l11.99023,-5.99805c1.28658,1.57841 3.1642,2.65234 5.3457,2.65234c3.84823,0 7,-3.15178 7,-7c0,-3.84822 -3.15177,-7 -7,-7zM36,8c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4zM12,20c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4zM36,32c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4z">
              </path>
            </g>
          </g>
        </svg>
      </button>
      <!-- Admin Profile (only shown when logged in) -->
      <?php if (is_logged_in()): ?>
        <div class="admin-profile">
          <button class="profile-toggle" aria-label="Admin profile" aria-expanded="false">
            <div class="profile-avatar-large">
              <?= substr(htmlspecialchars($_SESSION['username']), 0, 1) ?>
            </div>
          </button>
          <div class="profile-dropdown">
            <div class="profile-header">
              <h4>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h4>
              <p><?= htmlspecialchars($_SESSION['email']) ?></p>
            </div>
            <ul class="admin-menu">
              <li><a href="/admin/dashboard"><svg xmlns="http://www.w3.org/2000/svg" height="24px"
                    viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
                    <path
                      d="M418-340q25 25 63 23.5t55-27.5l224-336-336 224q-26 18-28.5 54.5T418-340ZM204-160q-22 0-40.5-9.5T134-198q-26-47-40-97.5T80-400q0-83 31.5-156T197-683q54-54 127-85.5T480-800q82 0 154 31t126 84.5q54 53.5 86 125T879-406q1 55-12.5 107.5T825-198q-11 19-29.5 28.5T755-160H204Z" />
                  </svg> Dashboard</a></li>
              <li><a href="/admin/posts"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                    width="24px" fill="#ffffe3">
                    <path
                      d="M160-120q-33 0-56.5-23.5T80-200v-640l67 67 66-67 67 67 67-67 66 67 67-67 67 67 66-67 67 67 67-67 66 67 67-67v640q0 33-23.5 56.5T800-120H160Zm0-80h280v-240H160v240Zm360 0h280v-80H520v80Zm0-160h280v-80H520v80ZM160-520h640v-120H160v120Z" />
                  </svg> Posts</a></li>
              <li><a href="/admin/biography"><svg xmlns="http://www.w3.org/2000/svg" height="24px"
                    viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
                    <path
                      d="M480-160q-48-38-104-59t-116-21q-42 0-82.5 11T100-198q-21 11-40.5-1T40-234v-482q0-11 5.5-21T62-752q46-24 96-36t102-12q58 0 113.5 15T480-740v484q51-32 107-48t113-16q36 0 70.5 6t69.5 18v-480q15 5 29.5 10.5T898-752q11 5 16.5 15t5.5 21v482q0 23-19.5 35t-40.5 1q-37-20-77.5-31T700-240q-60 0-116 21t-104 59Zm80-200v-380l200-200v400L560-360Z" />
                  </svg> Biography</a></li>
              <li><a href="/admin/project"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                    width="24px" fill="#ffffe3">
                    <path
                      d="M160-120q-33 0-56.5-23.5T80-200v-440q0-33 23.5-56.5T160-720h160v-80q0-33 23.5-56.5T400-880h160q33 0 56.5 23.5T640-800v80h160q33 0 56.5 23.5T880-640v440q0 33-23.5 56.5T800-120H160Zm240-600h160v-80H400v80Z" />
                  </svg> Project</a></li>
              <li><a href="/admin/faq"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                    width="24px" fill="#ffffe3">
                    <path
                      d="M470-200h-10q-142 0-241-99t-99-241q0-142 99-241t241-99q71 0 132.5 26.5t108 73q46.5 46.5 73 108T800-540q0 134-75.5 249T534-111q-10 5-20 5.5t-18-4.5q-8-5-14-13t-7-19l-5-58Zm-11-121q17 0 29-12t12-29q0-17-12-29t-29-12q-17 0-29 12t-12 29q0 17 12 29t29 12Zm-87-304q11 5 22 .5t18-14.5q9-12 21-18.5t27-6.5q24 0 39 13.5t15 34.5q0 13-7.5 26T480-558q-25 22-37 41.5T431-477q0 12 8.5 20.5T460-448q12 0 20-9t12-21q5-17 18-31t24-25q21-21 31.5-42t10.5-42q0-46-31.5-74T460-720q-32 0-59 15.5T357-662q-6 11-1.5 21.5T372-625Z" />
                  </svg> FAQ</a></li>
              <li><a href="/admin/user"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                    width="24px" fill="#ffffe3">
                    <path
                      d="m640-120-12-60q-12-5-22.5-10.5T584-204l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628-460l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732-180l-12 60h-80ZM80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-29 72-24 143t48 135H80Zm600-80q33 0 56.5-23.5T760-320q0-33-23.5-56.5T680-400q-33 0-56.5 23.5T600-320q0 33 23.5 56.5T680-240ZM400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Z" />
                  </svg> User</a></li>
              <li><a href="/admin/contact">
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                    fill="#ffffe3">
                    <path
                      d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-287q5 0 10.5-1.5T501-453l283-177q8-5 12-12.5t4-16.5q0-20-17-30t-35 1L480-520 212-688q-18-11-35-.5T160-659q0 10 4 17.5t12 11.5l283 177q5 3 10.5 4.5T480-447Z" />
                  </svg> Messages</a></li>
              <li><a href="/admin/settings"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                    width="24px" fill="#ffffe3">
                    <path
                      d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm112-260q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Z" />
                  </svg> Settings</a></li>
            </ul>
            <div class="profile-footer">
              <a href="/logout" class="logout-btn">Logout</a>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Mobile Navigation Toggle -->
      <button class="mobile-nav-toggle" aria-controls="mobile-navigation" aria-expanded="false">
        <span class="sr-only">Menu</span>
        <span class="hamburger"></span>
      </button>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobile-navigation">
      <nav>
        <ul>
          <li><a href="/home" class="nav-link">HOME</a></li>
          <li><a href="/about" class="nav-link">ABOUT</a></li>
          <li><a href="/project" class="nav-link">PROJECT</a></li>
          <li><a href="/gallery" class="nav-link">GALLERY</a></li>
          <li><a href="/contact" class="nav-link">CONTACT</a></li>
        </ul>

        <div class="mobile-social-links">
          <a href="<?= htmlspecialchars($settings['instagram']) ?>" target="_blank" aria-label="Instagram">
            <i class="fab fa-instagram"></i> Instagram
          </a>
          <a href="<?= htmlspecialchars($settings['youtube']) ?>" target="_blank" aria-label="YouTube">
            <i class="fab fa-youtube"></i> YouTube
          </a>
          <a href="<?= htmlspecialchars($settings['facebook']) ?>" target="_blank" aria-label="Facebook">
            <i class="fab fa-facebook"></i> Facebook
          </a>
        </div>

        <div class="mobile-controls">
          <button class="mobile-share-btn">
            <i class="fas fa-share-alt"></i> Share This Page
          </button>
        </div>
      </nav>
    </div>

    <!-- Share Popup -->
    <div class="share-popup">
      <div class="share-content">
        <h3>Show Us Some Love</h3>
        <p>Tell the world about me</p>
        <div class="share-url">
          <input type="text" value="webdummy.com" readonly id="share-url">
          <button class="copy-btn" aria-label="Copy link">Copy Link</button>
        </div>
        <button class="close-share" aria-label="Close share popup">&times;</button>
      </div>
    </div>
  </div>
</header>

<style type="text/css">
  /* Header Container */
  header#nav {
    position: sticky;
    align-self: center;
    top: 0;
    width: 100%;
    max-width: 1920px;
    z-index: 1000;
  }

  .nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    height: 70px;
    padding: 0 5%;
    margin: 0 auto;
    transition: var(--transition);
    background-color: inherit;
    /* Inherit from header */
  }

  .nav-container.scrolled {
    height: 60px;
  }

  /* Logo */
  .logo {
    display: flex;
    align-items: center;
  }

  .logo img {
    height: 50px;
    transition: var(--transition);
  }

  .nav-container.scrolled .logo img {
    height: 45px;
  }

  /* Desktop Navigation */
  .desktop-nav {
    display: flex;
    align-items: center;
    flex-grow: 1;
    justify-content: center;
  }

  .desktop-nav ul {
    display: flex;
    gap: 25px;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .nav-link {
    color: var(--primaryFont);
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    position: relative;
    padding: 5px 0;
    transition: var(--transition);
  }

  .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--accentBlue);
    transition: var(--transition);
  }

  .nav-link:hover::after {
    width: 100%;
  }

  /* Nav Controls */
  .nav-controls {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  /* Share Button */
  .profile-toggle {
    background: var(--accentBlue);
  }

  .social-button {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    width: 25px;
    height: 25px;
  }

  .share-btn {
    background: var(--tertiary);
  }

  .share-btn,
  .profile-toggle {
    border-style: solid;
    border-color: var(--secondary);
    color: var(--text-light);
    font-size: 1.2rem;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
  }

  .share-btn:hover,
  .profile-toggle:hover {
    background: var(--secondary);
    border-color: var(--accentBlue);
  }

  /* Admin Profile */
  .admin-profile {
    position: relative;
  }

  .profile-toggle {
    font-size: 1.5rem;
  }

  .profile-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 250px;
    background: var(--secondary);
    border-radius: 8px;
    padding: 15px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: var(--transition);
    z-index: 1001;
  }

  .admin-profile:hover .profile-dropdown,
  .profile-toggle[aria-expanded="true"]+.profile-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }

  .profile-header {
    padding-bottom: 10px;
    margin-bottom: 10px;
    border-bottom: 1px solid var(--borderColor);
  }

  .profile-header h4 {
    margin: 0;
    color: var(--text-light);
    font-size: 1rem;
  }

  .profile-header p {
    margin: 5px 0 0;
    color: var(--text-light);
    font-size: 0.8rem;
    opacity: 0.8;
  }

  .admin-menu {
    list-style: none;
    padding: 0;
    margin: 0 0 10px;
  }

  .admin-menu li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: var(--text-light);
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition);
  }

  .admin-menu li a:hover {
    color: var(--accentBlue);
  }

  .admin-menu li a i {
    width: 20px;
    text-align: center;
  }

  .profile-footer {
    padding-top: 10px;
    border-top: 1px solid var(--borderColor);
  }

  .logout-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 4px;
    border-style: solid;
    border-color: red;
    gap: 10px;
    color: red;
    text-decoration: none;
    padding: 8px 0;
    font-size: 0.9rem;
    transition: var(--transition);
  }

  .logout-btn:hover {
    background-color: red;
    color: #fff;
  }

  /* Share Popup */
  .share-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1002;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
  }


  .share-popup.active {
    opacity: 1;
    visibility: visible;
  }

  .share-content {
    background: var(--primary);
    padding: 30px;
    border-radius: 10px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    position: relative;
  }

  .share-content h3 {
    margin-top: 0;
    color: var(--text-light);
  }

  .share-content p {
    color: var(--text-light);
    margin-bottom: 20px;
  }

  .share-url {
    display: flex;
    margin-bottom: 20px;
  }

  .share-url input {
    flex-grow: 1;
    padding: 10px;

    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 4px 0 0 4px;
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-light);
  }

  .copy-btn {
    background: var(--secondary);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
    transition: var(--transition);
  }

  .copy-btn:hover {
    background: var(--secondary-dark);
  }

  .close-share {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    color: var(--text-light);
    font-size: 1.5rem;
    cursor: pointer;
  }

  /* Mobile Navigation Toggle */

  .mobile-nav-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    z-index: 1001;
    color: var(--primaryFont);
  }

  .hamburger {
    display: block;
    width: 25px;
    height: 2px;
    background: var(--primaryFont);
    position: relative;
    transition: var(--transition);
  }

  .hamburger::before,
  .hamburger::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    background: var(--primaryFont);
    transition: var(--transition);
  }

  .hamburger::before {
    top: -8px;
  }

  .hamburger::after {
    bottom: -8px;
  }

  .mobile-nav-toggle.open .hamburger {
    background: transparent;
  }

  .mobile-nav-toggle.open .hamburger::before {
    transform: rotate(45deg);
    top: 0;
  }

  .mobile-nav-toggle.open .hamburger::after {
    transform: rotate(-45deg);
    bottom: 0;
  }

  /* Mobile Navigation */
  .mobile-nav {
    position: fixed;
    top: 0;
    right: -100%;
    width: 80%;
    max-width: 400px;
    height: 100vh;
    background: var(--primary);
    padding: 100px 30px 30px;
    z-index: 1000;
    transition: var(--transition);
    overflow-y: auto;
  }

  .mobile-nav.active {
    right: 0;
  }

  .mobile-nav ul {
    list-style: none;
    padding: 0;
    margin-bottom: 30px;
  }

  .mobile-nav li {
    margin-bottom: 20px;
  }

  .mobile-nav .nav-link {
    font-size: 1.2rem;
    display: block;
    padding: 10px 0;
  }

  /* Mobile Social Links */
  .mobile-social-links {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
  }

  .mobile-social-links a {
    color: var(--text-light);
    text-decoration: none;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: var(--transition);
  }


  /* Mobile Controls */
  .mobile-controls {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  .mobile-share-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 4px;
    color: var(--text-light);
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
  }

  /* Accessibility */
  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
  }

  /* Responsive */
  @media (max-width: 992px) {

    .desktop-nav,
    .social-button {
      display: none;
    }

    .mobile-nav-toggle {
      display: block;
    }

    .nav-container {
      padding: 0 20px;
    }
  }

  @media (max-width: 768px) {
    .nav-controls {
      gap: 10px;
    }

    .share-btn,
    .profile-toggle {
      width: 36px;
      height: 36px;
      font-size: 1.1rem;
    }
  }

  @media (max-width: 576px) {
    .logo img {
      height: 40px;
    }

    .nav-container.scrolled .logo img {
      height: 45px;
    }
  }
</style>
<script>
  // Mobile Navigation Toggle - fixed version
  document.addEventListener('DOMContentLoaded', function () {
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const mobileNav = document.getElementById('mobile-navigation');

    if (mobileNavToggle && mobileNav) {
      mobileNavToggle.addEventListener('click', () => {
        const isExpanded = mobileNavToggle.getAttribute('aria-expanded') === 'true';
        mobileNavToggle.setAttribute('aria-expanded', !isExpanded);
        mobileNav.classList.toggle('active');

        // Toggle hamburger animation
        mobileNavToggle.classList.toggle('open');
      });
    }

    // Close mobile nav when clicking on a link
    const mobileNavLinks = document.querySelectorAll('.mobile-nav .nav-link');
    mobileNavLinks.forEach(link => {
      link.addEventListener('click', () => {
        mobileNav.classList.remove('active');
        mobileNavToggle.setAttribute('aria-expanded', 'false');
        mobileNavToggle.classList.remove('open');
        document.body.style.overflow = 'auto';
      });
    });

    // Header scroll effect
    window.addEventListener('scroll', () => {
      const navContainer = document.querySelector('.nav-container');
      if (window.scrollY > 50) {
        navContainer.classList.add('scrolled');
      } else {
        navContainer.classList.remove('scrolled');
      }
    });

    // Share Popup - fixed version
    const shareBtn = document.querySelector('.share-btn');
    const mobileShareBtn = document.querySelector('.mobile-share-btn');
    const sharePopup = document.querySelector('.share-popup');
    const closeShare = document.querySelector('.close-share');
    const copyBtn = document.querySelector('.copy-btn');

    function setupSharePopup() {
      if (shareBtn) {
        shareBtn.addEventListener('click', () => {
          // Set current page URL
          const shareUrlInput = document.getElementById('share-url');
          shareUrlInput.value = window.location.href;
          sharePopup.classList.add('active');
          document.body.style.overflow = 'hidden';
        });
      }

      if (mobileShareBtn) {
        mobileShareBtn.addEventListener('click', () => {
          // Set current page URL
          const shareUrlInput = document.getElementById('share-url');
          shareUrlInput.value = window.location.href;
          sharePopup.classList.add('active');
          if (mobileNav) mobileNav.classList.remove('active');
          if (mobileNavToggle) {
            mobileNavToggle.setAttribute('aria-expanded', 'false');
            mobileNavToggle.classList.remove('open');
          }
          document.body.style.overflow = 'hidden';
        });
      }

      if (closeShare) {
        closeShare.addEventListener('click', () => {
          sharePopup.classList.remove('active');
          document.body.style.overflow = 'auto';
        });
      }

      if (copyBtn) {
        copyBtn.addEventListener('click', async () => {
          const shareUrl = document.getElementById('share-url');
          try {
            await navigator.clipboard.writeText(shareUrl.value);
            // Change button text temporarily
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Copied!';
            setTimeout(() => {
              copyBtn.textContent = originalText;
            }, 2000);
          } catch (err) {
            console.error('Failed to copy: ', err);
            // Fallback for older browsers
            shareUrl.select();
            document.execCommand('copy');
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Copied!';
            setTimeout(() => {
              copyBtn.textContent = originalText;
            }, 2000);
          }
        });
      }
    }

    setupSharePopup();

    // Profile Dropdown (for touch devices)
    const profileToggle = document.querySelector('.profile-toggle');
    if (profileToggle) {
      profileToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        const isExpanded = profileToggle.getAttribute('aria-expanded') === 'true';
        profileToggle.setAttribute('aria-expanded', !isExpanded);
      });

      // Close when clicking outside
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.admin-profile')) {
          profileToggle.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
</script>