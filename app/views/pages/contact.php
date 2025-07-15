<?php
require_once __DIR__ . '/../../../config.php';
// Page Configuration
$pageTitle = 'Contact Us';
$pageCSS = '/public/css/contact.css';

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
  $subject = trim($_POST['subject'] ?? '');
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

  if (empty($subject)) {
    $errors[] = "Subject is required.";
  } elseif (strlen($subject) > 200) {
    $errors[] = "Subject is too long.";
  }

  if (empty($message)) {
    $errors[] = "Message is required.";
  } elseif (strlen($message) > 5000) {
    $errors[] = "Message is too long.";
  }

  // Check for spam keywords
  $spamKeywords = ['http://', 'https://', 'www.', '.com', 'buy now', 'click here', 'viagra', 'casino'];
  foreach ($spamKeywords as $keyword) {
    if (stripos($message, $keyword) !== false || stripos($subject, $keyword) !== false) {
      $errors[] = "Your message contains suspicious content.";
      break;
    }
  }

  // If no errors, save to database
  if (empty($errors)) {
    try {
      $pdo = getPDO(); // Your database connection function

      $stmt = $pdo->prepare("INSERT INTO contact_messages 
                (name, email, subject, message, ip_address, user_agent) 
                VALUES (:name, :email, :subject, :message, :ip, :ua)");

      $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':subject' => $subject,
        ':message' => $message,
        ':ip' => $_SERVER['REMOTE_ADDR'],
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? ''
      ]);

      $success = true;

      // Optionally send email notification
      // $to = "your@email.com";
      // $headers = "From: $email";
      // mail($to, "New Contact Form Submission: $subject", $message, $headers);

    } catch (PDOException $e) {
      $errors[] = "There was an error submitting your message. Please try again later.";
      // Log the error for admin
      error_log("Contact form error: " . $e->getMessage());
    }
  }
}

ob_start();
?>
<!-- Content Start -->
<main class="content">
  <div class="seperator"></div>
  <div class="contact-hero">
    <h1>Let's Connect</h1>
    <p class="subtitle">We'd love to hear from you</p>
    <div class="floating-shapes">
      <div class="shape circle"></div>
      <div class="shape triangle"></div>
      <div class="shape square"></div>
    </div>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger" data-aos="fade-up">
      <h3>Error</h3>
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php elseif ($success): ?>
    <div class="alert alert-success" data-aos="fade-up">
      <h3>Thank You!</h3>
      <p>Your message has been sent successfully. We'll get back to you soon.</p>
    </div>
  <?php endif; ?>

  <div class="contact-grid">
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
        <input type="email" id="email" name="email" required
          value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
        <label for="email">Email Address</label>
        <div class="underline"></div>
      </div>

      <div class="form-group floating-label">
        <input type="text" id="subject" name="subject" required
          value="<?= isset($subject) ? htmlspecialchars($subject) : '' ?>">
        <label for="subject">Subject</label>
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
</main>
<style>
  /* Base Styles */
  .contact-hero {
    text-align: center;
    margin-bottom: 80px;
  }

  .contact-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 16px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
  }

  .subtitle {
    font-size: 1.2rem;
    color: #64748b;
    max-width: 600px;
    margin: 0 auto;
  }

  @keyframes float {

    0%,
    100% {
      transform: translateY(0) rotate(0deg);
    }

    50% {
      transform: translateY(-20px) rotate(5deg);
    }
  }

  /* Contact Grid Layout */
  .contact-grid {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* Contact Form */
  .contact-form {
    background: var(--secondary);
    border-radius: 12px;
    padding: 40px;
    width: 40%;
    min-width: 500px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  }

  .form-group {
    margin-bottom: 30px;
    position: relative;
  }

  .floating-label input,
  .floating-label textarea {
    width: 100%;
    padding: 12px 0;
    border: none;
    border-bottom: 1px solid #e2e8f0;
    font-size: 1rem;
    background: transparent;
    transition: all 0.3s;
  }

  .floating-label textarea {
    resize: none;
  }

  .floating-label label {
    position: absolute;
    top: 12px;
    left: 0;
    color: #94a3b8;
    transition: all 0.3s;
    pointer-events: none;
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
    top: -20px;
    font-size: 0.8rem;
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
    --background-hover: #0D0F18;
    --shadow: rgba(0, 9, 61, 0.12);
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
    margin: 30px auto 0;
  }

  .button .inner {
    padding: 14px 20px;
    transition: transform .2s, background .4s;
    color: var(--color);
    position: relative;
    display: flex;
    z-index: 1;
    min-width: 190px;
    border-radius: 13px;
    background: var(--b, var(--background));
    box-shadow: 0 1px 3px var(--shadow), 0 3px 7px var(--shadow);
    transform: scale(var(--scale, 1)) translateZ(0);
  }

  .button .inner:active {
    --scale: .95;
  }

  .button .inner:hover {
    --b: var(--background-hover);
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

  /* Animations */
  [data-aos] {
    opacity: 0;
    transition: opacity 0.6s, transform 0.6s;
  }

  [data-aos="fade-up"] {
    transform: translateY(20px);
  }

  [data-aos].aos-animate {
    opacity: 1;
    transform: translateY(0);
  }

  .alert {
    padding: 20px;
    border-radius: 8px;
    margin: 20px auto;
    max-width: 800px;
  }

  .alert-danger {
    background-color: #fee2e2;
    border-left: 4px solid #dc2626;
    color: #b91c1c;
  }

  .alert-success {
    background-color: #dcfce7;
    border-left: 4px solid #16a34a;
    color: #166534;
  }

  .alert h3 {
    margin-top: 0;
    margin-bottom: 10px;
  }

  .alert ul {
    margin-bottom: 0;
    padding-left: 20px;
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
    // Only animate if form is valid
    if (this.checkValidity()) {
      e.preventDefault(); // Prevent immediate submission for animation

      // Play the full button animation
      timeline.play();

      // Actually submit the form after animation completes
      setTimeout(() => {
        this.submit();
      }, 2000);
    }
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
</script>
<!-- Include footer -->
<?php include __DIR__ . '\..\..\..\app\views\partials\footer.php'; ?>
<!-- Content End -->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/layout.php'; ?>