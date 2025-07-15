<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_faq'])) {
    // Add new FAQ entry
    $stmt = $pdo->prepare("INSERT INTO faqs (question, answer, display_order) VALUES (?, ?, ?)");
    $stmt->execute([
      $_POST['question'],
      $_POST['answer'],
      $_POST['display_order']
    ]);
  } elseif (isset($_POST['update_faq'])) {
    // Update existing entry
    $stmt = $pdo->prepare("UPDATE faqs SET question=?, answer=?, display_order=? WHERE id=?");
    $stmt->execute([
      $_POST['question'],
      $_POST['answer'],
      $_POST['display_order'],
      $_POST['id']
    ]);
  } elseif (isset($_POST['delete_faq'])) {
    // Delete entry
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id=?");
    $stmt->execute([$_POST['id']]);
  }

  header("Location: faq.php");
  exit();
}

// Get all FAQ entries
$stmt = $pdo->prepare("SELECT * FROM faqs ORDER BY display_order ASC");
$stmt->execute();
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page configuration
$pageTitle = 'Manage FAQs';
ob_start();
?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap');

  .admin-container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  .admin-form,
  .admin-list {
    background-color: var(--secondary);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 30px;
  }

  .entry-card {
    background-color: var(--secondary);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 20px;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 7px;
  }

  .form-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
  }

  .button {
    padding: 10px 17px;
    font: 15px Ubuntu;
    color: white;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
  }

  .button span {
    cursor: pointer;
    display: inline-block;
    position: relative;
    transition: 0.5s;
  }

  .button span:after {
    position: absolute;
    opacity: 0;
    top: 0;
    right: -20px;
    transition: 0.5s;
  }

  .button:hover span {
    padding-right: 25px;
  }

  .button:hover span:after {
    opacity: 1;
    right: 0;
  }

  .btn-primary {
    background-color: rgb(85, 131, 241);
    border: 1px solid rgb(85, 131, 241);
  }

  .btn-primary span:after {
    font-family: FontAwesome;
    content: "\f067";
    /* plus icon */
  }

  .btn-secondary {
    background-color: rgb(39, 166, 75);
    border: 1px solid rgb(39, 166, 75);
  }

  .btn-secondary span:after {
    font-family: FontAwesome;
    content: "\f044";
    /* edit icon */
  }

  .btn-danger {
    background-color: rgb(242, 42, 42);
    border: 1px solid rgb(255, 0, 0)
  }

  .btn-danger span:after {
    font-family: FontAwesome;
    content: "\f1f8";
    /* trash icon */
  }

  h1,
  h2 {
    color: var(--primaryFont);
    margin-bottom: 20px;
  }
</style>

<div class="admin-container">
  <h1>Manage FAQ Entries</h1>

  <!-- Add New Entry Form -->
  <div class="admin-form">
    <h2>Add New FAQ</h2>
    <form method="POST">
      <div class="form-group">
        <label>Question</label>
        <input type="text" name="question" required>
      </div>
      <div class="form-group">
        <label>Answer</label>
        <textarea name="answer" rows="4" required></textarea>
      </div>
      <div class="form-group">
        <label>Display Order</label>
        <input type="number" name="display_order" required>
      </div>
      <button type="submit" name="add_faq" class="button btn-primary"><span>Add FAQ</span></button>
    </form>
  </div>

  <!-- Existing Entries -->
  <div class="admin-list">
    <h2>Current FAQs</h2>
    <?php foreach ($faqs as $faq): ?>
      <div class="entry-card">
        <form method="POST">
          <input type="hidden" name="id" value="<?= $faq['id'] ?>">
          <div class="form-group">
            <label>Question</label>
            <input type="text" name="question" value="<?= htmlspecialchars($faq['question']) ?>" required>
          </div>
          <div class="form-group">
            <label>Answer</label>
            <textarea name="answer" rows="4" required><?= htmlspecialchars($faq['answer']) ?></textarea>
          </div>
          <div class="form-group">
            <label>Display Order</label>
            <input type="number" name="display_order" value="<?= $faq['display_order'] ?>" required>
          </div>
          <div class="form-actions">
            <button type="submit" name="update_faq" class="button btn-secondary"><span>Update</span></button>
            <button type="submit" name="delete_faq" class="button btn-danger"
              onclick="return confirm('Are you sure?')"><span>Delete</span></button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="https://kit.fontawesome.com/ade0b34805.js" crossorigin="anonymous"></script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/adminlayout.php';
?>