<?php
$settings = get_settings($pdo);
// Process form submission
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Anti-spam measures
  if (!empty($_POST['website'])) {
    // Honeypot field was filled out - likely spam
    die();
  }

  // Time check - if form submitted too quickly
  if (isset($_POST['timestamp']) && (time() - (int) $_POST['timestamp']) < 5) {
    $errors[] = "Please take your time to fill out the form.";
  }

  // Validate inputs
  $name = trim($_POST['name'] ?? '');
  $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
  $message = trim($_POST['message'] ?? '');

  if (empty($name)) {
    $errors[] = "Name is required.";
  } elseif (strlen($name) > 100) {
    $errors[] = "Name is too long.";
  }

  if (empty($email)) {
    $errors[] = "Email is required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  } elseif (strlen($email) > 100) {
    $errors[] = "Email is too long.";
  }

  if (empty($message)) {
    $errors[] = "Message is required.";
  } elseif (strlen($message) > 5000) {
    $errors[] = "Message is too long.";
  }

  // Check for spam keywords
  $spamKeywords = ['http://', 'https://', 'www.', '.com', 'buy now', 'click here', 'viagra', 'casino'];
  foreach ($spamKeywords as $keyword) {
    if (stripos($message, $keyword) !== false) {
      $errors[] = "Your message contains suspicious content.";
      break;
    }
  }

  // If no errors, save to database
  if (empty($errors)) {
    try {
      $pdo = getPDO(); // Your database connection function

      $stmt = $pdo->prepare(query: "INSERT INTO contact_messages 
                (name, email, message, ip_address, user_agent) 
                VALUES (:name, :email, :message, :ip, :ua)");

      $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message,
        ':ip' => $_SERVER['REMOTE_ADDR'],
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? ''
      ]);

      $success = true;

    } catch (PDOException $e) {
      $errors[] = "There was an error submitting your message. Please try again later.";
      // Log the error for admin
      error_log("Contact form error: " . $e->getMessage());
    }
  }
}
?>
<!-- Content Start -->
<section class="text-wrapper">
  <h1> — NEVYLLO KALANGI — CONTENT CREATOR</h1>
  <h1> — NEVYLLO KALANGI — EVENT MANAGER</h1>
  <h1> — NEVYLLO KALANGI — CREATIVE DEVELOPER</h1>
</section>
<section class="contact">
  <div class="contact-col">
    <h1>DROP US<br>A LINE</h1>
  </div>
  <div class="contact-col">
    <div class="contact-row">
      <form class="contact-form" data-aos="fade-up" method="POST" action="">
        <!-- Honeypot field -->
        <input type="text" name="website" style="display: none;">
        <!-- Timestamp for spam detection -->
        <input type="hidden" name="timestamp" value="<?= time() ?>">

        <div class="form-group floating-label">
          <input type="text" id="name" name="name" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">
          <label for="name">Your Name</label>
          <div class="underline"></div>
        </div>

        <div class="form-group floating-label">
          <input type="email" id="email" name="email" requiredw
            value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
          <label for="email">Email Address</label>
          <div class="underline"></div>
        </div>

        <div class="form-group floating-label">
          <textarea id="message" name="message" rows="4"
            required><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>
          <label for="message">Your Message</label>
          <div class="underline"></div>
        </div>

        <button type="submit" class="button" id="submit-button">
          <div class="inner">
            <div class="icon">
              <div class="person">
                <div class="arm"></div>
                <div class="arm right"></div>
                <div class="leg"></div>
                <div class="leg right"></div>
              </div>
              <div class="weight"></div>
            </div>
            <div class="text">
              <span>Send Message</span>
              <span>Message Sent!</span>
            </div>
          </div>
        </button>
      </form>
    </div>
  </div>
  <div class="contact-col">
    <div>◼ EXPLORE</div>
    <a href="/" class="contact-link">Home</a>
    <a href="/about" class="contact-link">About</a>
    <a href="/project" class="contact-link">Project</a><br>
    <div>◼ STALK ME</div>
    <a class="contact-social-button" href="<?= htmlspecialchars($settings['instagram']) ?>" target="_blank"
      aria-label="Instagram">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256"
        height="256" viewBox="0 0 256 256" xml:space="preserve">
        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
          transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
          <path
            d="M 62.263 90 H 27.738 C 12.443 90 0 77.557 0 62.263 V 27.738 C 0 12.443 12.443 0 27.738 0 h 34.525 C 77.557 0 90 12.443 90 27.738 v 34.525 C 90 77.557 77.557 90 62.263 90 z M 27.738 7 C 16.303 7 7 16.303 7 27.738 v 34.525 C 7 73.697 16.303 83 27.738 83 h 34.525 C 73.697 83 83 73.697 83 62.263 V 27.738 C 83 16.303 73.697 7 62.263 7 H 27.738 z"
            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,227); fill-rule: nonzero; opacity: 1;"
            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
          <path
            d="M 45.255 70.47 c -13.904 0 -25.215 -11.312 -25.215 -25.215 S 31.352 20.04 45.255 20.04 s 25.215 11.312 25.215 25.215 S 59.159 70.47 45.255 70.47 z M 45.255 27.04 c -10.044 0 -18.215 8.171 -18.215 18.215 c 0 10.044 8.171 18.215 18.215 18.215 c 10.044 0 18.215 -8.171 18.215 -18.215 C 63.471 35.211 55.3 27.04 45.255 27.04 z"
            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,227); fill-rule: nonzero; opacity: 1;"
            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
          <circle cx="70.557" cy="19.936999999999998" r="4.897"
            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,227); fill-rule: nonzero; opacity: 1;"
            transform="  matrix(1 0 0 1 0 0) " />
        </g>
      </svg>
      @nevyllokalangi on Instagram
    </a>
    <a class="contact-social-button" href="<?= htmlspecialchars($settings['tiktok']) ?>" target="_blank"
      aria-label="Tiktok">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256"
        height="256" viewBox="0 0 256 256" xml:space="preserve">
        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
          transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
          <path
            d="M 0 10.934 v 68.132 C 0 85.105 4.895 90 10.934 90 h 68.132 C 85.105 90 90 85.105 90 79.066 V 10.934 C 90 4.895 85.105 0 79.066 0 H 10.934 C 4.895 0 0 4.895 0 10.934 z M 71.039 33.277 v 6.758 c -3.187 0.001 -6.283 -0.623 -9.203 -1.855 c -1.878 -0.793 -3.627 -1.814 -5.227 -3.048 l 0.048 20.801 c -0.02 4.684 -1.873 9.085 -5.227 12.4 c -2.73 2.698 -6.188 4.414 -9.937 4.97 c -0.881 0.13 -1.777 0.197 -2.684 0.197 c -4.013 0 -7.823 -1.3 -10.939 -3.698 c -0.586 -0.452 -1.147 -0.941 -1.681 -1.468 c -3.635 -3.593 -5.509 -8.462 -5.194 -13.584 c 0.241 -3.899 1.802 -7.618 4.404 -10.532 c 3.443 -3.857 8.26 -5.998 13.41 -5.998 c 0.906 0 1.803 0.068 2.684 0.198 v 2.499 v 6.951 c -0.835 -0.275 -1.727 -0.427 -2.656 -0.427 c -4.705 0 -8.512 3.839 -8.442 8.548 c 0.045 3.013 1.69 5.646 4.118 7.098 c 1.141 0.682 2.453 1.105 3.853 1.182 c 1.097 0.06 2.151 -0.093 3.126 -0.415 c 3.362 -1.111 5.787 -4.268 5.787 -7.992 l 0.011 -13.93 V 16.5 h 9.307 c 0.009 0.922 0.103 1.822 0.276 2.694 c 0.702 3.529 2.692 6.591 5.46 8.678 c 2.414 1.821 5.42 2.9 8.678 2.9 c 0.002 0 0.029 0 0.027 -0.002 V 33.277 z"
            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,227); fill-rule: nonzero; opacity: 1;"
            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
        </g>
      </svg>
      @nevyllokalangi on TikTok
    </a>
    <a class="contact-social-button" href="<?= htmlspecialchars($settings['youtube']) ?>" target="_blank"
      aria-label="Youtube">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256"
        height="256" viewBox="0 0 256 256" xml:space="preserve">
        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
          transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
          <path
            d="M 88.119 23.338 c -1.035 -3.872 -4.085 -6.922 -7.957 -7.957 C 73.144 13.5 45 13.5 45 13.5 s -28.144 0 -35.162 1.881 c -3.872 1.035 -6.922 4.085 -7.957 7.957 C 0 30.356 0 45 0 45 s 0 14.644 1.881 21.662 c 1.035 3.872 4.085 6.922 7.957 7.957 C 16.856 76.5 45 76.5 45 76.5 s 28.144 0 35.162 -1.881 c 3.872 -1.035 6.922 -4.085 7.957 -7.957 C 90 59.644 90 45 90 45 S 90 30.356 88.119 23.338 z M 36 58.5 v -27 L 59.382 45 L 36 58.5 z"
            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,227); fill-rule: nonzero; opacity: 1;"
            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
        </g>
      </svg>
      @nevyllokalangi on YouTube
    </a>
    <a class="contact-social-button" href="<?= htmlspecialchars($settings['github']) ?>" target="_blank"
      aria-label="Github">
      <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 432 432">
        <path fill="currentColor"
          d="M43 3h341q18 0 30.5 12.5T427 45v342q0 17-12.5 29.5T384 429H274q-7-1-7-21v-58q0-27-15-40q44-5 70.5-27t26.5-78q0-33-22-57q11-26-2-57q-18-6-58 22q-26-7-54-7t-53 7q-18-12-32.5-17.5T107 91h-6q-12 31-2 57q-22 24-22 57q0 55 27 77.5t70 27.5q-11 10-13 29q-42 18-62-18q-12-20-33-22q-2 0-4.5.5T56 303t8 9q15 7 24 31q1 2 2 4.5t6.5 9.5t13 10.5T130 374t30-2v36q0 20-8 21H43q-18 0-30.5-12.5T0 387V45q0-17 12.5-29.5T43 3z" />
      </svg>
      nevyllokalangi on GitHub
    </a><br>

    <div>◼ CONTACT</div>
    <div>For all enquiries:</div>
    <a class="contact-social-button" href="mailto:<?= htmlspecialchars($settings['mail']) ?>" target="_blank"
      aria-label="Linkedin">
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
        <path
          d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-287q5 0 10.5-1.5T501-453l283-177q8-5 12-12.5t4-16.5q0-20-17-30t-35 1L480-520 212-688q-18-11-35-.5T160-659q0 10 4 17.5t12 11.5l283 177q5 3 10.5 4.5T480-447Z" />
      </svg>
      nevyllokalangi@gmail.com
    </a><br>
    <!-- Portfolio Download Section -->
    <?php include __DIR__ . '/../partials/portfolio.php'; ?>
  </div>
</section>
<style>
  /* Scrolling Text Section */
  .text-wrapper {
    display: flex;
    background: var(--color-text-primary);
    overflow: hidden;
    width: 100%;
    padding: 1rem 0;
  }

  .text-wrapper h1 {
    font-size: 3rem;
    color: black;
    letter-spacing: 1px;
    animation: move-rtl 16s linear infinite;
    font-weight: 700;
    text-transform: uppercase;
  }

  @keyframes move-rtl {
    0% {
      transform: translateX(0);
    }

    100% {
      transform: translateX(-100%);
    }
  }

  /* Base Styles */
  .contact {
    display: flex;
    padding: 0;
    width: 100vw;
    max-width: 1920px;
    background: var(--color-bg-secondary);
  }

  .contact-col {
    display: flex;
    justify-content: center;
    padding: 1.8rem;
    border: 2px solid white;
  }

  .contact-col:nth-child(3) {
    flex-direction: column;
    align-items: flex-start;
    font-size: .7rem;
    flex: 6;
    width: 100%;
  }

  .contact-link {
    transition: color 0.3s ease;
  }

  .contact-link:hover {
    color: var(--color-accent-hover);
  }

  .contact-col:nth-child(3) a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--color-text-primary);
  }

  .contact-col:nth-child(3) svg {
    height: .7rem;
    width: .7rem;
    margin: 5px;
  }

  .contact-col h1 {
    font-family: "Dharma", sans-serif;
    font-size: 17rem;
    letter-spacing: .5rem;
    line-height: .8;
  }

  /* Contact Form */
  .contact-form {
    width: 100%;
    min-width: 400px;
  }

  .form-group {
    margin-bottom: 15px;
    position: relative;
  }

  .floating-label input,
  .floating-label textarea {
    width: 100%;
    padding: 10px 0;
    border: none;
    border-bottom: 1px solid #e2e8f0;
    font-size: .7rem;
    background: transparent;
    transition: all 0.3s;
  }

  .floating-label textarea {
    resize: none;
  }

  .floating-label label {
    pointer-events: none;
    position: absolute;
    top: 12px;
    left: 0;
    font-size: .7rem;
    color: var(--color-text-muted);
    transition: all 0.3s;
  }

  .floating-label input:focus,
  .floating-label textarea:focus,
  .floating-label input:not(:placeholder-shown),
  .floating-label textarea:not(:placeholder-shown) {
    outline: none;
    border-bottom-color: #3b82f6;
  }

  .floating-label input:focus+label,
  .floating-label textarea:focus+label,
  .floating-label input:not(:placeholder-shown)+label,
  .floating-label textarea:not(:placeholder-shown)+label {
    top: -6px;
    font-size: .6rem;
    color: #3b82f6;
  }

  .underline {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: #3b82f6;
    transition: width 0.3s;
  }

  .floating-label input:focus~.underline,
  .floating-label textarea:focus~.underline {
    width: 100%;
  }

  /* Button Styles */
  .button {
    --color: #F6F8FF;
    --background: #171827;
    --person: #F6F8FF;
    --person-arm: var(--person);
    --person-leg: #D1D6EE;
    --weight: #275EFE;
    --weight-disk: #5C86FF;
    --person-y: 0;
    --weight-y: 0;
    --arm-rotate: 40;
    --arm-rotate-s: -40;
    --arm-rotate-s-x: 0;
    --leg-y: -2;
    --leg-rotate: 45;
    --leg-rotate-s: -70;
    display: table;
    outline: none;
    border: none;
    background: none;
    padding: 0;
    position: relative;
    cursor: pointer;
    line-height: 24px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    -webkit-appearance: none;
    -webkit-tap-highlight-color: transparent;
  }

  .button .inner {
    padding: 14px 20px;
    transition: transform .2s, background .4s;
    color: var(--color);
    position: relative;
    display: flex;
    z-index: 1;
    min-width: 190px;
    background: var(--b, var(--background));
    transform: scale(var(--scale, 1)) translateZ(0);
  }

  .button .inner:active {
    --scale: .95;
  }

  .button .icon {
    width: 24px;
    height: 24px;
    margin-right: 12px;
    display: block;
    position: relative;
  }

  .button .person,
  .button .weight {
    position: absolute;
  }

  .button .person {
    top: 7px;
    left: 9px;
    width: 6px;
    height: 10px;
    transform: translateY(calc(var(--person-y) * 1px));
  }

  .button .person:before,
  .button .person:after {
    content: '';
    position: absolute;
    left: 0;
  }

  .button .person:before {
    top: -5px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--person);
    transform: scale(.7);
    transform-origin: 50% 25%;
  }

  .button .person:after {
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
    border-radius: 2px 2px 3px 3px;
    background: var(--person);
  }

  .button .arm,
  .button .leg {
    position: absolute;
    left: var(--left, 0);
    top: var(--top, 0);
    width: 2px;
    height: 7px;
    border-radius: 1px;
    transform-origin: 1px 1px;
    background: var(--background);
    transform: translate(calc(var(--x, 0) * 1px), calc(var(--y, 0) * 1px)) rotateZ(calc(var(--rotate, 0) * 1deg));
  }

  .button .arm:before,
  .button .leg:before {
    content: '';
    position: absolute;
    left: 0;
    top: 5px;
    width: 2px;
    height: 7px;
    border-radius: 1px;
    transform-origin: 1px 1px;
    background: inherit;
    transform: rotateZ(calc(var(--rotate-s, 0) * 1deg)) rotateX(calc(var(--rotate-s-x, 0) * 1deg));
  }

  .button .arm.right,
  .button .leg.right {
    --left: 4px;
    transform: translate(calc(var(--x, 0) * -1px), calc(var(--y, 0) * 1px)) rotateZ(calc(var(--rotate, 0) * -1deg));
  }

  .button .arm.right:before,
  .button .leg.right:before {
    transform: rotateZ(calc(var(--rotate-s, 0) * -1deg)) rotateX(calc(var(--rotate-s-x, 0) * 1deg));
  }

  .button .arm {
    --background: var(--person-arm);
    --rotate: var(--arm-rotate);
    --rotate-s: var(--arm-rotate-s);
    --rotate-s-x: var(--arm-rotate-s-x);
    z-index: 1;
  }

  .button .leg {
    --top: 8px;
    --background: var(--person-leg);
    --y: var(--leg-y);
    --rotate: var(--leg-rotate);
    --rotate-s: var(--leg-rotate-s);
  }

  .button .weight {
    top: 17px;
    left: 0;
    width: 24px;
    height: 2px;
    border-radius: 1px;
    background: var(--weight);
    transform: translateY(calc(var(--weight-y, 0) * 1px));
  }

  .button .weight:before,
  .button .weight:after {
    content: '';
    position: absolute;
    border-radius: 1px;
    left: var(--l, 1px);
    top: var(--t, -2px);
    width: var(--w, 2px);
    height: var(--h, 6px);
    background: var(--weight-disk);
    box-shadow: var(--bx, 20px) 0 0 var(--weight-disk);
  }

  .button .weight:after {
    --l: 3px;
    --t: -3px;
    --h: 8px;
    --bx: 16px;
  }

  .button .text {
    position: relative;
  }

  .button .text span {
    display: block;
    white-space: nowrap;
    opacity: var(--o, 1);
    transform: translateX(var(--x, 0));
    transition: transform .3s, opacity .2s;
  }

  .button .text span:last-child {
    --o: 0;
    --x: 8px;
    position: absolute;
    left: 0;
    top: 0;
  }

  .button .ripple {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: inherit;
    pointer-events: none;
  }

  .button .ripple:before {
    content: '';
    position: absolute;
    top: calc(var(--ripple-y, 0) * 1px);
    left: calc(var(--ripple-x, 0) * 1px);
    transform: translate(-50%, -50%) scale(0);
    opacity: 0;
    width: 200%;
    padding-bottom: 200%;
    border-radius: 50%;
    background: currentColor;
    animation: ripple .5s ease-in;
  }

  .button i {
    position: absolute;
    display: block;
    width: 4px;
    height: 4px;
    top: 50%;
    left: 50%;
    margin: -2px 0 0 -2px;
    opacity: var(--o, 0);
    background: var(--b);
    transform: translate(var(--x), var(--y)) scale(var(--s, 1));
  }

  .button.complete .text span:first-child {
    --o: 0;
    --x: -8px;
  }

  .button.complete .text span:last-child {
    --o: 1;
    --x: 0;
  }

  .button.confetti i {
    animation: confetti .6s ease-out forwards;
  }

  @keyframes confetti {
    from {
      transform: translate(0, 0);
      opacity: 1;
    }
  }

  @keyframes ripple {
    25% {
      opacity: .07;
    }

    100% {
      transform: translate(-50%, -50%) scale(1);
    }
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
<script>
  // Form submission handler
  const contactForm = document.querySelector('.contact-form');
  const submitButton = document.querySelector('#submit-button');

  // Confetti setup
  let confettiAmount = 100,
    confettiColors = [
      '#7d32f5',
      '#f6e434',
      '#63fdf1',
      '#e672da',
      '#295dfe',
      '#6e57ff'
    ],
    random = (min, max) => {
      return Math.floor(Math.random() * (max - min + 1) + min);
    },
    createConfetti = to => {
      let elem = document.createElement('i'),
        set = Math.random() < 0.5 ? -1 : 1;
      elem.style.setProperty('--x', random(-360, 360) + 'px');
      elem.style.setProperty('--y', random(-200, 200) + 'px');
      elem.style.setProperty('--r', random(0, 360) + 'deg');
      elem.style.setProperty('--s', random(.6, 1));
      elem.style.setProperty('--b', confettiColors[random(0, 5)]);
      to.appendChild(elem);
    };

  // Button animation timeline
  let complete = false,
    timeline = gsap.timeline({
      paused: true,
      ease: 'none',
      onComplete() {
        complete = true;
        submitButton.classList.add('complete');
        for (let i = 0; i < confettiAmount; i++) {
          createConfetti(submitButton);
        }
        setTimeout(() => {
          submitButton.classList.add('confetti');
          setTimeout(() => submitButton.querySelectorAll('i').forEach(i => i.remove()), 600);
        }, 300);
        // Reset
        setTimeout(() => {
          submitButton.classList.remove('complete', 'confetti');
          complete = false;
        }, 2000);
      }
    }),
    up = 0;

  timeline.to(submitButton, {
    keyframes: [{
      '--weight-y': -6,
      '--arm-rotate-s-x': 90,
      duration: .3
    }, {
      '--weight-y': -10,
      '--arm-rotate-s-x': 45,
      '--arm-rotate-s': 130,
      duration: .2
    }, {
      '--weight-y': -12,
      '--arm-rotate-s': 130,
      '--arm-rotate-s-x': 0,
      duration: .1
    }, {
      '--weight-y': -20,
      '--person-y': -4,
      '--arm-rotate': 100,
      '--arm-rotate-s': 90,
      '--leg-y': 0,
      '--leg-rotate': 20,
      '--leg-rotate-s': -20,
      duration: .2
    }, {
      '--weight-y': -25,
      '--arm-rotate': 150,
      '--arm-rotate-s': 30,
      duration: .2
    }]
  });

  // Button click handler
  submitButton.addEventListener('click', e => {
    if (!contactForm.checkValidity()) {
      return; // Don't animate if the form is invalid
    }

    up = 1;

    const rippleDiv = document.createElement('div');
    rippleDiv.className = 'ripple';
    const boundingClientRect = submitButton.getBoundingClientRect();
    submitButton.style.setProperty('--ripple-x', e.clientX - boundingClientRect.left);
    submitButton.style.setProperty('--ripple-y', e.clientY - boundingClientRect.top);
    submitButton.querySelector('.inner').appendChild(rippleDiv);
    setTimeout(() => rippleDiv.remove(), 500);
  });

  // Animation loop
  setInterval(() => {
    up = up > 0 ? up - .1 : 0;
    let progress = timeline.progress(),
      direction = up > 0 ? 1 : -1.5;

    if (!complete) {
      timeline.progress(progress + .01 * direction);
    }
  }, 1000 / 60);

  // Form submission
  contactForm.addEventListener('submit', function (e) {
    // Prevent default submission initially
    e.preventDefault();

    // Check form validity again (for safety)
    if (!this.checkValidity()) {
      return;
    }

    // Prevent multiple animations
    if (timeline.isActive() || complete) {
      return;
    }

    // Start animation
    timeline.restart();

    // Submit form after animation finishes and confetti shows
    setTimeout(() => {
      this.submit(); // Actually submit the form (to PHP)
    }, 2000); // Same as animation duration
  });

  // Simple animation on scroll
  document.addEventListener('DOMContentLoaded', () => {
    const animateOnScroll = () => {
      const elements = document.querySelectorAll('[data-aos]');
      elements.forEach(el => {
        const rect = el.getBoundingClientRect();
        const isVisible = rect.top < window.innerHeight - 100;
        if (isVisible) {
          el.classList.add('aos-animate');
        }
      });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on load
  });

  // Form interaction
  const formGroups = document.querySelectorAll('.floating-label');
  formGroups.forEach(group => {
    const input = group.querySelector('input, textarea');
    const label = group.querySelector('label');

    // Initialize placeholder to trigger floating labels
    input.setAttribute('placeholder', ' ');

    input.addEventListener('focus', () => {
      label.style.color = '#3b82f6';
    });

    input.addEventListener('blur', () => {
      label.style.color = input.value ? '#3b82f6' : '#94a3b8';
    });
  });
  submitButton.disabled = true;
  setTimeout(() => {
    submitButton.disabled = false;
  }, 3000);
</script>