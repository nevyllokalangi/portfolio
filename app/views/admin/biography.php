<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

// Pagination settings - 1 entry per page
$entriesPerPage = 1;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($currentPage < 1) {
  $currentPage = 1;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_biography'])) {
    // Validate and sanitize input
    $displayOrder = (int) $_POST['display_order'];
    $subtitle = trim($_POST['subtitle']);
    $year = trim($_POST['year']);
    $heading = trim($_POST['heading']);
    $description = trim($_POST['description']);
    $imagePath = trim($_POST['image_path']);

    // Check if display_order already exists
    $checkStmt = $pdo->prepare("SELECT id FROM biography WHERE display_order = ?");
    $checkStmt->execute([$displayOrder]);

    if ($checkStmt->rowCount() > 0) {
      // Find next available display order
      $newOrder = $displayOrder;
      do {
        $newOrder++;
        $checkStmt->execute([$newOrder]);
      } while ($checkStmt->rowCount() > 0);

      // Update the display order to the next available
      $displayOrder = $newOrder;
    }

    // Add new biography entry
    $stmt = $pdo->prepare("INSERT INTO biography (subtitle, year, heading, description, image_path, display_order) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
      $subtitle,
      $year,
      $heading,
      $description,
      $imagePath,
      $displayOrder
    ]);

    // Get the ID of the newly inserted entry
    $newId = $pdo->lastInsertId();

    // Count total entries to determine the page number for the new entry
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM biography");
    $stmt->execute();
    $totalEntries = $stmt->fetchColumn();
    $pageForNewEntry = ceil($totalEntries / $entriesPerPage);

    header("Location: ../admin/biography?page=" . $pageForNewEntry);
    exit();

  } elseif (isset($_POST['update_biography'])) {
    $id = (int) $_POST['id'];
    $newDisplayOrder = (int) $_POST['display_order'];
    $currentDisplayOrder = (int) $_POST['current_display_order']; // Added hidden field in form

    // Only check for conflicts if display order changed
    if ($newDisplayOrder != $currentDisplayOrder) {
      $checkStmt = $pdo->prepare("SELECT id FROM biography WHERE display_order = ? AND id != ?");
      $checkStmt->execute([$newDisplayOrder, $id]);

      if ($checkStmt->rowCount() > 0) {
        // Find next available display order
        $adjustedOrder = $newDisplayOrder;
        do {
          $adjustedOrder++;
          $checkStmt->execute([$adjustedOrder, $id]);
        } while ($checkStmt->rowCount() > 0);

        $newDisplayOrder = $adjustedOrder;
      }
    }

    // Update existing entry
    $stmt = $pdo->prepare("UPDATE biography SET subtitle=?, year=?, heading=?, description=?, image_path=?, display_order=? WHERE id=?");
    $stmt->execute([
      trim($_POST['subtitle']),
      trim($_POST['year']),
      trim($_POST['heading']),
      trim($_POST['description']),
      trim($_POST['image_path']),
      $newDisplayOrder,
      $id
    ]);

    header("Location: ../admin/biography?page=" . $currentPage);
    exit();

  } elseif (isset($_POST['delete_biography'])) {
    // First get the display_order of the entry being deleted
    $stmt = $pdo->prepare("SELECT display_order FROM biography WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $deletedOrder = $stmt->fetchColumn();

    // Delete the entry
    $stmt = $pdo->prepare("DELETE FROM biography WHERE id = ?");
    $stmt->execute([$_POST['id']]);

    // Adjust higher-numbered entries down by one
    $stmt = $pdo->prepare("
            UPDATE biography 
            SET display_order = display_order - 1 
            WHERE display_order > ?
        ");
    $stmt->execute([$deletedOrder]);

    // After deletion, adjust the current page if needed
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM biography");
    $stmt->execute();
    $totalEntries = $stmt->fetchColumn();
    $totalPages = ceil($totalEntries / $entriesPerPage);

    $redirectPage = $currentPage;
    if ($currentPage > $totalPages && $totalPages > 0) {
      $redirectPage = $totalPages;
    }

    header("Location: ../admin/biography?page=" . $redirectPage);
    exit();

  } elseif (isset($_POST['reorder_all'])) {
    // Get all entries ordered by current display_order
    $stmt = $pdo->prepare("SELECT id FROM biography ORDER BY display_order ASC, id ASC");
    $stmt->execute();
    $entries = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Update each with sequential order
    foreach ($entries as $index => $id) {
      $order = $index + 1; // Start at 1
      $stmt = $pdo->prepare("UPDATE biography SET display_order = ? WHERE id = ?");
      $stmt->execute([$order, $id]);
    }

    header("Location: ../admin/biography");
    exit();
  }
}

// Get total number of entries
$stmt = $pdo->prepare("SELECT COUNT(*) FROM biography");
$stmt->execute();
$totalEntries = $stmt->fetchColumn();

// Calculate total pages
$totalPages = max(1, ceil($totalEntries / $entriesPerPage));

// Adjust current page if it's beyond total pages
if ($currentPage > $totalPages) {
  $currentPage = $totalPages;
}

// Get current entry
$offset = ($currentPage - 1) * $entriesPerPage;
$stmt = $pdo->prepare("SELECT * FROM biography ORDER BY display_order ASC, id ASC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $entriesPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$currentEntry = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all entries for display order reference
$allEntriesStmt = $pdo->prepare("SELECT id, display_order FROM biography ORDER BY display_order ASC");
$allEntriesStmt->execute();
$allEntries = $allEntriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Page configuration
$pageTitle = 'Manage Biography';
ob_start();
?>

<style>
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
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.09), 0 6px 15px 0 rgba(0, 0, 0, 0.09);
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
  }

  .btn-secondary {
    background-color: rgb(39, 166, 75);
    border: 1px solid rgb(39, 166, 75);
  }

  .btn-secondary span:after {
    font-family: FontAwesome;
    content: "\f044";
  }

  .btn-danger {
    background-color: rgb(242, 42, 42);
    border: 1px solid rgb(242, 42, 42);
  }

  .btn-danger span:after {
    font-family: FontAwesome;
    content: "\f1f8";
  }

  h1,
  h2 {
    color: var(---primaryFont);
    margin-bottom: 20px;
  }

  /* Animation for better UX */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .entry-card {
    animation: fadeIn 0.3s ease forwards;
  }

  /* Pagination styles */
  .pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 30px;
    padding: 20px 0;
  }

  .pagination\:container {
    display: flex;
    align-items: center;
  }

  .arrow\:text {
    display: block;
    vertical-align: middle;
    font-size: 13px;
    vertical-align: middle;
  }

  .pagination\:number {
    --size: 32px;
    --margin: 6px;
    margin: 0 var(--margin);
    border-radius: 6px;
    background: #202020;
    max-width: auto;
    min-width: var(--size);
    height: var(--size);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 0 6px;
  }

  .pagination\:number:hover {
    background: lighten(#202020, 3%);
  }

  .pagination\:number:active {
    background: lighten(#202020, 3%);
  }

  .pagination\:active {
    background: lighten(#202020, 3%);
    position: relative;
  }

  .hide {
    display: none;
    visibility: hidden;
    height: 0;
  }

  .entry-nav {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .entry-position {
    font-weight: bold;
    color: var(--text-color);
  }
</style>

<div class="admin-container">
  <h1>Manage Biography Entries</h1>

  <!-- Add New Entry Form -->
  <div class="admin-form">
    <h2>Add New Entry</h2>
    <form method="POST">
      <div class="form-group">
        <label>Subtitle (Early Years/College Years)</label>
        <input type="text" name="subtitle" required>
      </div>
      <div class="form-group">
        <label>Year(s)</label>
        <input type="text" name="year" required>
      </div>
      <div class="form-group">
        <label>Heading</label>
        <input type="text" name="heading" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4" required></textarea>
      </div>
      <div class="form-group">
        <label>Image Path (optional)</label>
        <input type="text" name="image_path">
      </div>
      <div class="form-group">
        <label>Display Order</label>
        <input type="number" name="display_order" min="1" value="<?= $totalEntries + 1 ?>" required>
      </div>
      <button type="submit" name="add_biography" class="button btn-primary"><span>Add Entry</span></button>
    </form>
  </div>

  <!-- Existing Entry -->
  <div class="admin-list">
    <h2>Edit Entry</h2>

    <?php if ($totalEntries > 0): ?>
      <div class="entry-nav">
        <div class="entry-position">
          Entry <?= $currentPage ?> of <?= $totalEntries ?>
        </div>
        <form method="POST">
          <button type="submit" name="reorder_all" class="button" style="background-color: #666;">
            <span>Reorder All Sequentially</span>
          </button>
        </form>
      </div>

      <div class="entry-card">
        <form method="POST">
          <input type="hidden" name="id" value="<?= $currentEntry['id'] ?>">
          <input type="hidden" name="current_display_order" value="<?= $currentEntry['display_order'] ?>">

          <div class="form-group">
            <label>Subtitle</label>
            <input type="text" name="subtitle" value="<?= htmlspecialchars($currentEntry['subtitle']) ?>" required>
          </div>
          <div class="form-group">
            <label>Year(s)</label>
            <input type="text" name="year" value="<?= htmlspecialchars($currentEntry['year']) ?>" required>
          </div>
          <div class="form-group">
            <label>Heading</label>
            <input type="text" name="heading" value="<?= htmlspecialchars($currentEntry['heading']) ?>" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"
              required><?= htmlspecialchars($currentEntry['description']) ?></textarea>
          </div>
          <div class="form-group">
            <label>Image Path</label>
            <input type="text" name="image_path" value="<?= htmlspecialchars($currentEntry['image_path'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Display Order</label>
            <input type="number" name="display_order" min="1" value="<?= $currentEntry['display_order'] ?>" required>
            <small>Current order sequence:
              <?php
              foreach ($allEntries as $entry) {
                echo $entry['display_order'];
                if ($entry['id'] == $currentEntry['id']) {
                  echo ' (Current)';
                }
                echo ' → ';
              }
              ?>
            </small>
          </div>
          <div class="form-actions">
            <button type="submit" name="update_biography" class="button btn-secondary"><span>Update</span></button>
            <button type="submit" name="delete_biography" class="button btn-danger"
              onclick="return confirm('Are you sure you want to delete this entry?')"><span>Delete</span></button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <p>No biography entries found. Add your first entry using the form above.</p>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($totalEntries > 1): ?>
    <div class="pagination-container">
      <div class="pagination:container">
        <?php if ($currentPage > 1): ?>
          <div class="pagination:number arrow">
            <a href="?page=<?= $currentPage - 1 ?>"
              style="display: flex; align-items: center; text-decoration: none; color: inherit;">
              <svg width="18" height="18">
                <use xlink:href="#left" />
              </svg>
              <span class="arrow:text">Previous</span>
            </a>
          </div>
        <?php endif; ?>

        <?php
        // Show limited page numbers for single entry pagination
        $maxVisiblePages = 5;
        $startPage = max(1, $currentPage - floor($maxVisiblePages / 2));
        $endPage = min($totalEntries, $startPage + $maxVisiblePages - 1);

        if ($endPage - $startPage + 1 < $maxVisiblePages) {
          $startPage = max(1, $endPage - $maxVisiblePages + 1);
        }

        if ($startPage > 1) {
          echo '<div class="pagination:number"><a href="?page=1" style="text-decoration: none; color: inherit;">1</a></div>';
          if ($startPage > 2) {
            echo '<div class="pagination:number">...</div>';
          }
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
          $activeClass = $i == $currentPage ? 'pagination:active' : '';
          echo '<div class="pagination:number ' . $activeClass . '"><a href="?page=' . $i . '" style="text-decoration: none; color: inherit;">' . $i . '</a></div>';
        }

        if ($endPage < $totalEntries) {
          if ($endPage < $totalEntries - 1) {
            echo '<div class="pagination:number">...</div>';
          }
          echo '<div class="pagination:number"><a href="?page=' . $totalEntries . '" style="text-decoration: none; color: inherit;">' . $totalEntries . '</a></div>';
        }
        ?>

        <?php if ($currentPage < $totalEntries): ?>
          <div class="pagination:number arrow">
            <a href="?page=<?= $currentPage + 1 ?>"
              style="display: flex; align-items: center; text-decoration: none; color: inherit;">
              <svg width="18" height="18">
                <use xlink:href="#right" />
              </svg>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<svg class="hide">
  <symbol id="left" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
  </symbol>
  <symbol id="right" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
  </symbol>
</svg>

<script src="https://kit.fontawesome.com/ade0b34805.js" crossorigin="anonymous"></script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/adminlayout.php';
?>