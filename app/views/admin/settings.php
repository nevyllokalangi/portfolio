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
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 col-xl-8 mx-auto">
        <h2 class="h3 mb-4 page-title">Settings</h2>
        <div class="my-4">
          <?php if (!empty($success)): ?>
            <div class="alert alert-success">Settings updated successfully!</div>
          <?php endif; ?>
          <form method="post">
            <div class="list-group mb-5 shadow">
              <div class="list-group-item">
                <label>Instagram
                  <input type="url" name="instagram" class="form-control"
                    value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>" />
                </label>
              </div>
              <div class="list-group-item">
                <label>LinkedIn
                  <input type="url" name="linkedin" class="form-control"
                    value="<?= htmlspecialchars($settings['linkedin'] ?? '') ?>" />
                </label>
              </div>
              <div class="list-group-item">
                <label>TikTok
                  <input type="url" name="tiktok" class="form-control"
                    value="<?= htmlspecialchars($settings['tiktok'] ?? '') ?>" />
                </label>
              </div>
              <div class="list-group-item">
                <label>YouTube
                  <input type="url" name="youtube" class="form-control"
                    value="<?= htmlspecialchars($settings['youtube'] ?? '') ?>" />
                </label>
              </div>
              <div class="list-group-item">
                <label>Location
                  <input type="text" name="location" class="form-control"
                    value="<?= htmlspecialchars($settings['location'] ?? '') ?>" />
                </label>
              </div>
              <div class="list-group-item">
                <label>Portfolio Link 1
                  <input type="url" name="portfolio" class="form-control"
                    value="<?= htmlspecialchars($settings['portfolio'] ?? '') ?>" />
                </label>
              </div>
              <div class="list-group-item">
                <label>Portfolio Link 2
                  <input type="url" name="portfolio2" class="form-control"
                    value="<?= htmlspecialchars($settings['portfolio2'] ?? '') ?>" />
                </label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<style>
  /* Provided list-group styles */
  .list-group {
    display: flex;
    flex-direction: column;
    padding-left: 0;
    margin-bottom: 0;
    border-radius: 0.25rem;
  }

  .list-group-item-action {
    width: 100%;
    color: #4d5154;
    text-align: inherit;
  }

  .list-group-item-action:hover,
  .list-group-item-action:focus {
    z-index: 1;
    color: #4d5154;
    text-decoration: none;
    background-color: #f4f6f9;
  }

  .list-group-item-action:active {
    color: #8e9194;
    background-color: #eef0f3;
  }

  .list-group-item {
    position: relative;
    display: block;
    padding: 1.25rem 1.5rem;
    background-color: #ffffff;
    border: 1px solid #eef0f3;
    margin-bottom: 1.2rem;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
  }

  .list-group-item label {
    font-weight: 600;
    font-size: 1.08rem;
    margin-bottom: 0.5rem;
    display: block;
    color: #1b1b1b;
  }

  .list-group-item:first-child {
    border-top-left-radius: inherit;
    border-top-right-radius: inherit;
  }

  .list-group-item:last-child {
    border-bottom-right-radius: inherit;
    border-bottom-left-radius: inherit;
  }

  .list-group-item.disabled,
  .list-group-item:disabled {
    color: #6d7174;
    pointer-events: none;
    background-color: #ffffff;
  }

  .list-group-item.active {
    z-index: 2;
    color: #ffffff;
    background-color: #1b68ff;
    border-color: #1b68ff;
  }

  .list-group-item+.list-group-item {
    border-top-width: 0;
  }

  .list-group-item+.list-group-item.active {
    margin-top: -1px;
    border-top-width: 1px;
  }

  .btn.btn-primary {
    background: #1b68ff;
    color: #fff;
    border: none;
    padding: 0.7em 2.2em;
    border-radius: 0.25rem;
    font-weight: 600;
    font-size: 1.15rem;
    margin-top: 1.5rem;
    transition: background 0.2s;
    letter-spacing: 0.5px;
  }

  .btn.btn-primary:hover {
    background: #1558cc;
  }

  .form-control {
    display: block;
    width: 100%;
    padding: 0.6rem 1rem;
    font-size: 1.08rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    margin-top: 0.5rem;
    margin-bottom: 0.2rem;
  }

  .alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
    padding: 0.75rem 1.25rem;
    border-radius: 0.25rem;
    margin-bottom: 1.5rem;
    font-size: 1.08rem;
  }

  @media (max-width: 700px) {
    .list-group-item {
      padding: 1rem 0.7rem;
      margin-bottom: 0.8rem;
    }

    .btn.btn-primary {
      width: 100%;
      padding: 0.9em 0;
      font-size: 1.08rem;
    }
  }
</style>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/adminlayout.php'; ?>