<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../functions/helpers.php';
require_login();
require_role(ROLE_ADMIN);

$pageTitle = 'Settings';
$pageCSS = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'instagram' => $_POST['instagram'] ?? '',
    'linkedin' => $_POST['linkedin'] ?? '',
    'tiktok' => $_POST['tiktok'] ?? '',
    'youtube' => $_POST['youtube'] ?? '',
    'location' => $_POST['location'] ?? '',
    'portfolio' => $_POST['portfolio'] ?? '',
    'portfolio2' => $_POST['portfolio2'] ?? '',
  ];
  update_settings($pdo, $data);
  $success = true;
}
$settings = get_settings($pdo);

ob_start();
?>
<main class="content">
  <div class="container">

    <?php if (!empty($success)): ?>
      <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        <div>Settings updated successfully!</div>
      </div>
    <?php endif; ?>

    <div class="settings-grid">
      <div class="settings-card">
        <div class="card-header">
          <i class="fas fa-globe"></i>
          <h2>Social Media Profiles</h2>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="form-group">
              <label class="form-label">
                <i class="fab fa-instagram"></i>
                <span>Instagram</span>
              </label>
              <input type="url" name="instagram" class="form-control"
                value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>"
                placeholder="https://instagram.com/username">
            </div>

            <div class="form-group">
              <label class="form-label">
                <i class="fab fa-linkedin"></i>
                <span>LinkedIn</span>
              </label>
              <input type="url" name="linkedin" class="form-control"
                value="<?= htmlspecialchars($settings['linkedin'] ?? '') ?>"
                placeholder="https://linkedin.com/in/username">
            </div>

            <div class="form-group">
              <label class="form-label">
                <i class="fab fa-tiktok"></i>
                <span>TikTok</span>
              </label>
              <input type="url" name="tiktok" class="form-control"
                value="<?= htmlspecialchars($settings['tiktok'] ?? '') ?>" placeholder="https://tiktok.com/@username">
            </div>

            <div class="form-group">
              <label class="form-label">
                <i class="fab fa-youtube"></i>
                <span>YouTube</span>
              </label>
              <input type="url" name="youtube" class="form-control"
                value="<?= htmlspecialchars($settings['youtube'] ?? '') ?>" placeholder="https://youtube.com/@username">
            </div>
        </div>
      </div>

      <div class="settings-card">
        <div class="card-header">
          <i class="fas fa-link"></i>
          <h2>Portfolio & Location</h2>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-map-marker-alt"></i>
              <span>Location</span>
            </label>
            <input type="text" name="location" class="form-control"
              value="<?= htmlspecialchars($settings['location'] ?? '') ?>" placeholder="City, Country">
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-external-link-alt"></i>
              <span>Portfolio Link 1</span>
            </label>
            <input type="url" name="portfolio" class="form-control"
              value="<?= htmlspecialchars($settings['portfolio'] ?? '') ?>" placeholder="https://your-portfolio.com">
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-external-link-alt"></i>
              <span>Portfolio Link 2</span>
            </label>
            <input type="url" name="portfolio2" class="form-control"
              value="<?= htmlspecialchars($settings['portfolio2'] ?? '') ?>"
              placeholder="https://another-portfolio.com">
          </div>

          <div class="form-actions">
            <button type="reset" class="btn btn-reset">Reset</button>
            <button type="submit" class="btn btn-save">Save Settings</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  /* === Color Variables === */
  :root {
    --color-bg-primary: #0A0A12;
    --color-bg-secondary: #13131F;
    --color-bg-surface: #1A1A2E;
    --color-bg-navbar: #0D0D18;
    --color-text-primary: #E0E0E0;
    --color-text-secondary: #AAAAAA;
    --color-text-muted: #666666;
    --color-text-link: #00CFFD;
    --color-text-success: #4FFFB0;
    --color-text-error: #FF3C38;
    --color-accent-primary: #00CFFD;
    --color-accent-hover: #14E4FF;
    --color-accent-secondary: #537FE7;
    --color-accent-tertiary: #41EAD4;
    --color-button-primary-bg: #00CFFD;
    --color-button-primary-text: #0A0A12;
    --color-button-primary-hover: #14E4FF;
    --color-button-secondary-bg: #1A1A2E;
    --color-button-secondary-text: #00CFFD;
    --color-button-secondary-hover: #2B2B40;
    --color-border: #29293F;
    --color-success: #4FFFB0;
    --color-error: #FF3C38;
    --color-glow-primary: rgba(0, 207, 253, 0.4);
    --color-glow-link: rgba(0, 207, 253, 0.3);
    --color-shadow-card: rgba(0, 0, 0, 0.4);
  }

  /* === Settings Page Styles === */
  .content {
    padding: 2rem;
    background: var(--color-bg-primary);
    min-height: 100vh;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
  }

  .settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 1.8rem;
  }

  @media (max-width: 1100px) {
    .settings-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Settings Card */
  .settings-card {
    background: var(--color-bg-surface);
    border-radius: 16px;
    border: 1px solid var(--color-border);
    box-shadow: 0 10px 30px var(--color-shadow-card);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .settings-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
  }

  .card-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--color-border);
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .card-header i {
    font-size: 1.6rem;
    color: var(--color-accent-primary);
    width: 40px;
    height: 40px;
    background: rgba(0, 207, 253, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .card-header h2 {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--color-text-primary);
  }

  .card-body {
    padding: 2rem;
  }

  /* Form Styles */
  .form-group {
    margin-bottom: 1.8rem;
  }

  .form-label {
    display: block;
    margin-bottom: 0.8rem;
    font-weight: 500;
    color: var(--color-text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.05rem;
  }

  .form-label i {
    color: var(--color-accent-secondary);
    width: 24px;
    text-align: center;
    font-size: 1.2rem;
  }

  .form-control {
    width: 100%;
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: 10px;
    padding: 1rem 1.4rem;
    color: var(--color-text-primary);
    font-size: 1rem;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    outline: none;
    border-color: var(--color-accent-primary);
    box-shadow: 0 0 0 3px var(--color-glow-primary);
  }

  .form-control::placeholder {
    color: var(--color-text-muted);
    font-size: 0.95rem;
  }

  /* Success Message */
  .alert-success {
    background: rgba(79, 255, 176, 0.15);
    border: 1px solid var(--color-success);
    color: var(--color-text-success);
    padding: 1.2rem 1.8rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 1.05rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
  }

  .alert-success i {
    font-size: 1.6rem;
  }

  /* Buttons */
  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1.2rem;
    padding-top: 1.2rem;
    margin-top: 1rem;
  }

  .btn {
    padding: 0.9rem 2.2rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1.05rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
  }

  .btn-reset {
    background: var(--color-button-secondary-bg);
    color: var(--color-button-secondary-text);
    border: 1px solid var(--color-border);
  }

  .btn-reset:hover {
    background: var(--color-button-secondary-hover);
    color: var(--color-accent-hover);
    transform: translateY(-2px);
  }

  .btn-save {
    background: var(--color-button-primary-bg);
    color: var(--color-button-primary-text);
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(0, 207, 253, 0.3);
  }

  .btn-save:hover {
    background: var(--color-button-primary-hover);
    box-shadow: 0 6px 20px rgba(20, 228, 255, 0.4);
    transform: translateY(-2px);
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .settings-grid {
      grid-template-columns: 1fr;
    }

    .card-body {
      padding: 1.5rem;
    }

    .form-actions {
      flex-direction: column;
    }

    .btn {
      width: 100%;
      padding: 1rem;
    }

    .header-container {
      margin-bottom: 1.8rem;
    }

    .page-title {
      font-size: 1.8rem;
    }
  }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
  // Add focus effects to form elements
  document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.form-control');

    inputs.forEach(input => {
      input.addEventListener('focus', function () {
        this.parentElement.style.transform = 'translateY(-3px)';
        this.parentElement.style.transition = 'transform 0.3s ease';
      });

      input.addEventListener('blur', function () {
        this.parentElement.style.transform = 'none';
      });
    });

    // Add hover effect to buttons
    const buttons = document.querySelectorAll('.btn');

    buttons.forEach(button => {
      button.addEventListener('mouseenter', function () {
        this.style.transform = 'translateY(-3px)';
      });

      button.addEventListener('mouseleave', function () {
        this.style.transform = 'none';
      });
    });
  });
</script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/adminlayout.php'; ?>