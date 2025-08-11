<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Handle image upload with validation
  function handleImageUpload()
  {
    $uploadDir = __DIR__ . '/../../../public/uploads/biography/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK) {
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $fileType = finfo_file($finfo, $_FILES['image_upload']['tmp_name']);
      finfo_close($finfo);

      if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.'];
      }

      $maxSize = 4 * 1024 * 1024; // 4MB
      if ($_FILES['image_upload']['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size exceeds 4MB limit.'];
      }

      // Generate unique filename
      $extension = pathinfo($_FILES['image_upload']['name'], PATHINFO_EXTENSION);
      $filename = uniqid() . '.' . $extension;
      $targetPath = $uploadDir . $filename;

      if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => '/public/uploads/biography/' . $filename];
      }
    }
    return ['success' => false, 'path' => null, 'message' => 'No file uploaded or upload error'];
  }

  // Add new biography entry
  if (isset($_POST['add_biography'])) {
    $imagePath = null;
    if (!empty($_FILES['image_upload']['name'])) {
      $imageResult = handleImageUpload();
      if (!$imageResult['success']) {
        $_SESSION['error_message'] = $imageResult['message'] ?? 'Image upload failed';
        header("Location: ../admin/biography");
        exit();
      }
      $imagePath = $imageResult['path'];
    }

    try {
      $stmt = $pdo->prepare("INSERT INTO biography (subtitle, year, heading, description, image_path) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([
        htmlspecialchars(trim($_POST['subtitle'])),
        htmlspecialchars(trim($_POST['year'])),
        htmlspecialchars(trim($_POST['heading'])),
        htmlspecialchars(trim($_POST['description'])),
        $imagePath
      ]);
    } catch (Exception $e) {
      // Delete uploaded file if insert fails
      if ($imagePath) {
        $filePath = __DIR__ . '/../../../public' . $imagePath;
        if (file_exists($filePath) && is_file($filePath)) {
          unlink($filePath);
        }
      }
      $_SESSION['error_message'] = "Database error: " . $e->getMessage();
      header("Location: ../admin/biography");
      exit();
    }

    header("Location: ../admin/biography");
    exit();

  }
  // Update existing entry
  elseif (isset($_POST['update_biography'])) {
    $id = (int) $_POST['id'];
    $currentImagePath = $_POST['current_image_path'] ?? '';
    $newImagePath = $currentImagePath;

    // Handle image removal
    if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
      if ($currentImagePath) {
        $filePath = __DIR__ . '/../../../public' . $currentImagePath;
        if (file_exists($filePath) && is_file($filePath)) {
          if (!unlink($filePath)) {
            $_SESSION['error_message'] = "Failed to remove existing image";
            header("Location: ../admin/biography");
            exit();
          }
        }
      }
      $newImagePath = '';
    }
    // Handle new image upload
    elseif (!empty($_FILES['image_upload']['name'])) {
      $imageResult = handleImageUpload();
      if (!$imageResult['success']) {
        $_SESSION['error_message'] = $imageResult['message'] ?? 'Image upload failed';
        header("Location: ../admin/biography");
        exit();
      }

      // Delete old image BEFORE setting new path
      if ($currentImagePath) {
        $oldFilePath = __DIR__ . '/../../../public' . $currentImagePath;
        if (file_exists($oldFilePath) && is_file($oldFilePath)) {
          if (!unlink($oldFilePath)) {
            $_SESSION['error_message'] = "Failed to replace existing image";
            header("Location: ../admin/biography");
            exit();
          }
        }
      }
      $newImagePath = $imageResult['path'];
    }

    try {
      $stmt = $pdo->prepare("UPDATE biography SET subtitle=?, year=?, heading=?, description=?, image_path=? WHERE id=?");
      $stmt->execute([
        htmlspecialchars(trim($_POST['subtitle'])),
        htmlspecialchars(trim($_POST['year'])),
        htmlspecialchars(trim($_POST['heading'])),
        htmlspecialchars(trim($_POST['description'])),
        $newImagePath,
        $id
      ]);
    } catch (Exception $e) {
      $_SESSION['error_message'] = "Database error: " . $e->getMessage();
      header("Location: ../admin/biography");
      exit();
    }

    header("Location: ../admin/biography");
    exit();

  }
  // Delete entry
  elseif (isset($_POST['delete_biography'])) {
    $id = (int) $_POST['id'];

    try {
      $stmt = $pdo->prepare("SELECT image_path FROM biography WHERE id = ?");
      $stmt->execute([$id]);
      $imagePath = $stmt->fetchColumn();

      // Delete file if exists
      if ($imagePath) {
        $filePath = __DIR__ . '/../../../public' . $imagePath;
        if (file_exists($filePath) && is_file($filePath)) {
          if (!unlink($filePath)) {
            $_SESSION['error_message'] = "Failed to delete image file";
            header("Location: ../admin/biography");
            exit();
          }
        }
      }

      // Delete database entry
      $stmt = $pdo->prepare("DELETE FROM biography WHERE id = ?");
      $stmt->execute([$id]);

    } catch (Exception $e) {
      $_SESSION['error_message'] = "Delete failed: " . $e->getMessage();
      header("Location: ../admin/biography");
      exit();
    }

    header("Location: ../admin/biography");
    exit();
  }
}

// Fetch existing entries
try {
  $stmt = $pdo->prepare("SELECT * FROM biography ORDER BY year DESC, id DESC");
  $stmt->execute();
  $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  $entries = [];
  $_SESSION['error_message'] = "Failed to load entries: " . $e->getMessage();
}

// Page configuration
$pageTitle = 'Manage Biography';
ob_start();
?>

<style>
  .admin-container {
    width: 100vw;
    padding: 20px 5vw;
  }

  .admin-header {
    width: 100%;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .admin-header h1 {
    font-size: 2rem;
    font-weight: 600;
    color: var(--primaryFont);
    margin-bottom: 0.5rem;
  }

  .subtitle {
    color: var(--primaryFont);
    font-size: 0.875rem;
  }

  .table-container {
    background: var(--secondary);
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .biography-table {
    width: 100%;
    border-collapse: collapse;
  }

  .biography-table th,
  .biography-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--tertiary);
  }

  .biography-table th {
    background-color: var(--tertiary);
    font-weight: 600;
    color: var(--primaryFont);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
  }

  .biography-table tr:last-child td {
    border-bottom: none;
  }

  .biography-table tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
  }

  .action-cell {
    display: flex;
    gap: 8px;
  }

  .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
  }

  .modal-content {
    background-color: var(--secondary);
    border-radius: 0.5rem;
    padding: 25px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  .close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #aaa;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: var(--primaryFont);
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 7px;
    box-sizing: border-box;
    background-color: var(--tertiary);
    color: var(--primaryText);
  }

  .form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
  }

  .form-row .form-group {
    flex: 1;
    margin-bottom: 0;
  }

  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
  }

  /* Button Styles */
  .button {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.09), 0 6px 15px 0 rgba(0, 0, 0, 0.09);
    padding: 10px 24px;
    font: 15px Ubuntu;
    color: white;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    position: relative;
    overflow: hidden;
  }

  .button span {
    position: relative;
    transition: 0.3s;
    display: inline-block;
  }

  .button:hover span {
    transform: translateX(-15%);
  }

  .button svg {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: 0.3s;
  }

  .button:hover svg {
    opacity: 1;
    right: 10px;
  }

  .btn-primary {
    background-color: rgb(39, 166, 75);
  }

  .btn-secondary {
    background-color: rgb(85, 131, 241);
  }

  .btn-danger {
    background-color: rgb(242, 42, 42);
  }

  .btn-sm {
    padding: 6px 28px;
    font-size: 13px;
  }

  .add-entry-button {
    padding: 10px 20px;
    font-size: 16px;
  }

  .btn-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    background-color: #fee2e2;
    color: #b91c1c;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: background-color 0.2s;
    width: 32px;
    height: 32px;
  }

  .btn-delete:hover {
    background-color: #fecaca;
  }

  .btn-delete svg {
    width: 16px;
    height: 16px;
  }

  /* Image Upload Styles */
  .image-preview-container {
    margin: 15px 0;
    text-align: center;
  }

  .image-preview {
    max-width: 100%;
    max-height: 200px;
    border-radius: 7px;
    display: block;
    margin: 0 auto 10px;
  }

  .remove-image-container {
    margin-top: 10px;
    padding: 10px;
    background-color: rgba(255, 0, 0, 0.05);
    border-radius: 7px;
    border: 1px solid rgba(255, 0, 0, 0.2);
    display: flex;
    align-items: center;
  }

  .remove-image-container label {
    display: flex;
    align-items: center;
    cursor: pointer;
    color: #dc3545;
    font-size: 14px;
  }

  .remove-image-container input[type="checkbox"] {
    margin-right: 8px;
    width: 16px;
    height: 16px;
  }

  .file-input-container {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
  }

  .file-input-container input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
  }

  .file-input-label {
    display: block;
    padding: 10px 15px;
    background-color: var(--tertiary);
    border: 1px dashed #ccc;
    border-radius: 7px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .file-input-label:hover {
    background-color: rgba(85, 131, 241, 0.1);
    border-color: rgb(85, 131, 241);
  }
</style>

<div class="admin-container">
  <div class="admin-header">
    <div>
      <h1>Manage Biography Entries</h1>
      <p class="subtitle">Total Entries: <?= count($entries) ?></p>
    </div>
    <button class="button btn-primary add-entry-button" id="open-add-modal">
      <span>ADD</span>
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
        <path
          d="M440-440H240q-17 0-28.5-11.5T200-480q0-17 11.5-28.5T240-520h200v-200q0-17 11.5-28.5T480-760q17 0 28.5 11.5T520-720v200h200q17 0 28.5 11.5T760-480q0 17-11.5 28.5T720-440H520v200q0 17-11.5 28.5T480-200q-17 0-28.5-11.5T440-240v-200Z" />
      </svg>
    </button>
  </div>

  <!-- Entries Table -->
  <div class="table-container">
    <?php if (count($entries) > 0): ?>
      <table class="biography-table">
        <thead>
          <tr>
            <th>Year</th>
            <th>Subtitle</th>
            <th>Heading</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($entries as $entry): ?>
            <tr>
              <td><?= htmlspecialchars($entry['year']) ?></td>
              <td><?= htmlspecialchars($entry['subtitle']) ?></td>
              <td><?= htmlspecialchars($entry['heading']) ?></td>
              <td>
                <?php if (!empty($entry['image_path'])): ?>
                  <img src="<?= htmlspecialchars($entry['image_path']) ?>" alt="Bio image"
                    style="max-width: 50px; max-height: 50px; border-radius: 4px;">
                <?php else: ?>
                  <span style="color: #999;">No image</span>
                <?php endif; ?>
              </td>
              <td class="action-cell">
                <button class="button btn-secondary btn-sm edit-entry" data-id="<?= $entry['id'] ?>"
                  data-subtitle="<?= htmlspecialchars($entry['subtitle']) ?>"
                  data-year="<?= htmlspecialchars($entry['year']) ?>"
                  data-heading="<?= htmlspecialchars($entry['heading']) ?>"
                  data-description="<?= htmlspecialchars($entry['description']) ?>"
                  data-image-path="<?= htmlspecialchars($entry['image_path']) ?>">
                  <span>EDIT</span><svg xmlns="http://www.w3.org/2000/svg" height="12px" viewBox="0 -960 960 960"
                    width="24px" fill="#ffffe3">
                    <path
                      d="M160-120q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm544-528 56-56-56-56-56 56 56 56Z" />
                  </svg>
                </button>

                <form method="POST" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $entry['id'] ?>">
                  <button type="submit" name="delete_biography" class="button btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this entry?')">
                    <span>DELETE</span><svg xmlns="http://www.w3.org/2000/svg" height="12px" viewBox="0 -960 960 960"
                      width="24px" fill="#ffffe3">
                      <path
                        d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm120-160q17 0 28.5-11.5T440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280Zm160 0q17 0 28.5-11.5T600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280Z" />
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div style="padding: 2rem; text-align: center; color: var(--primaryFont);">
        No biography entries found. Add your first entry using the button above.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add Modal -->
<div class="modal" id="add-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Add New Entry</h2>
      <button class="close-modal">&times;</button>
    </div>

    <form method="POST" id="add-form" enctype="multipart/form-data">
      <input type="hidden" name="add_biography" value="1">

      <div class="form-row">
        <div class="form-group">
          <label>Subtitle (Early Years/College Years)</label>
          <input type="text" name="subtitle" required>
        </div>
        <div class="form-group">
          <label>Year(s)</label>
          <input type="text" name="year" required>
        </div>
      </div>

      <div class="form-group">
        <label>Heading</label>
        <input type="text" name="heading" required>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label>Bio Image</label>
        <div class="file-input-container">
          <input type="file" name="image_upload" id="add-image-upload" accept="image/*">
          <label class="file-input-label" for="add-image-upload">
            Choose an image (JPG, PNG, GIF, max 4MB)
          </label>
        </div>
        <div class="image-preview-container" id="add-image-preview" style="display: none;">
          <img src="" alt="Image preview" class="image-preview" id="add-preview-image">
          <button type="button" class="button btn-danger btn-sm" id="add-remove-preview">
            Remove Selected Image
          </button>
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="button btn-danger close-modal">
          <span>CANCEL</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
            <path
              d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
          </svg>
        </button>
        <button type="submit" class="button btn-primary">
          <span>ADD</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
            <path
              d="M440-440H240q-17 0-28.5-11.5T200-480q0-17 11.5-28.5T240-520h200v-200q0-17 11.5-28.5T480-760q17 0 28.5 11.5T520-720v200h200q17 0 28.5 11.5T760-480q0 17-11.5 28.5T720-440H520v200q0 17-11.5 28.5T480-200q-17 0-28.5-11.5T440-240v-200Z" />
          </svg>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="edit-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Entry</h2>
      <button class="close-modal">&times;</button>
    </div>

    <form method="POST" id="edit-form" enctype="multipart/form-data">
      <input type="hidden" name="id" id="edit-id">
      <input type="hidden" name="update_biography" value="1">
      <input type="hidden" name="current_image_path" id="edit-current-image-path">

      <div class="form-row">
        <div class="form-group">
          <label>Subtitle</label>
          <input type="text" name="subtitle" id="edit-subtitle" required>
        </div>
        <div class="form-group">
          <label>Year(s)</label>
          <input type="text" name="year" id="edit-year" required>
        </div>
      </div>

      <div class="form-group">
        <label>Heading</label>
        <input type="text" name="heading" id="edit-heading" required>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" id="edit-description" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label>Bio Image</label>

        <!-- Current image preview -->
        <div class="image-preview-container" id="current-image-container">
          <img src="" alt="Current image" class="image-preview" id="current-image">
          <div class="remove-image-container" id="remove-image-box">
            <label>
              <input type="checkbox" name="remove_image" value="1">
              Remove current image
            </label>
          </div>
        </div>

        <!-- New image upload -->
        <div class="file-input-container">
          <input type="file" name="image_upload" id="edit-image-upload" accept="image/*">
          <label class="file-input-label" for="edit-image-upload">
            Upload new image (JPG, PNG, GIF, max 4MB)
          </label>
        </div>
        <div class="image-preview-container" id="edit-image-preview" style="display: none;">
          <img src="" alt="New image preview" class="image-preview" id="edit-preview-image">
          <button type="button" class="button btn-danger btn-sm" id="edit-remove-preview">
            Remove New Image
          </button>
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="button btn-danger close-modal">
          <span>CANCEL</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
            <path
              d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
          </svg>
        </button>
        <button type="submit" class="button btn-secondary">
          <span>UPDATE</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="14px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
            <path
              d="M160-120q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm544-528 56-56-56-56-56 56 56 56Z" />
          </svg>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Remove image checkbox effect
  document.querySelector('input[name="remove_image"]')?.addEventListener('change', function () {
    if (this.checked) {
      document.getElementById('current-image').style.opacity = '0.5';
    } else {
      document.getElementById('current-image').style.opacity = '1';
    }
  });

  // Edit form image replacement confirmation
  document.getElementById('edit-form').addEventListener('submit', function (e) {
    const currentImagePath = document.getElementById('edit-current-image-path').value;
    const fileInput = this.querySelector('input[type="file"]');
    const removeImageChecked = document.querySelector('input[name="remove_image"]').checked;

    // Only ask for confirmation when replacing existing image
    if (currentImagePath && currentImagePath.trim() !== '' && fileInput.files.length > 0 && !removeImageChecked) {
      if (!confirm('Are you sure you want to replace the existing image?')) {
        e.preventDefault();
        fileInput.value = ''; // clear the file input
        return false;
      }
    }
  });

  // Modal control functions
  function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Add modal functionality
  document.getElementById('open-add-modal').addEventListener('click', () => {
    // Reset add form
    document.getElementById('add-form').reset();
    document.getElementById('add-image-preview').style.display = 'none';
    openModal('add-modal');
  });

  // Edit modal functionality
  const editButtons = document.querySelectorAll('.edit-entry');

  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      const id = button.dataset.id;
      const subtitle = button.dataset.subtitle;
      const year = button.dataset.year;
      const heading = button.dataset.heading;
      const description = button.dataset.description;
      const imagePath = button.dataset.imagePath;

      document.getElementById('edit-id').value = id;
      document.getElementById('edit-subtitle').value = subtitle;
      document.getElementById('edit-year').value = year;
      document.getElementById('edit-heading').value = heading;
      document.getElementById('edit-description').value = description;
      document.getElementById('edit-current-image-path').value = imagePath;

      // Handle image preview
      const currentImageContainer = document.getElementById('current-image-container');
      const currentImage = document.getElementById('current-image');
      const removeImageBox = document.getElementById('remove-image-box');

      if (imagePath) {
        currentImage.src = imagePath;
        currentImageContainer.style.display = 'block';
        removeImageBox.style.display = 'block';
      } else {
        currentImageContainer.style.display = 'none';
        removeImageBox.style.display = 'none';
      }

      // Reset new image preview
      document.getElementById('edit-image-preview').style.display = 'none';
      document.getElementById('edit-image-upload').value = '';
      document.querySelector('input[name="remove_image"]').checked = false;

      openModal('edit-modal');
    });
  });

  // Close modal functionality
  document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', () => {
      closeModal('add-modal');
      closeModal('edit-modal');
    });
  });

  // Close modal when clicking outside content
  window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
      closeModal('add-modal');
      closeModal('edit-modal');
    }
  });

  // Image preview for add form
  document.getElementById('add-image-upload').addEventListener('change', function (e) {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        document.getElementById('add-preview-image').src = event.target.result;
        document.getElementById('add-image-preview').style.display = 'block';
      }
      reader.readAsDataURL(file);
    }
  });

  // Remove preview for add form
  document.getElementById('add-remove-preview').addEventListener('click', function () {
    document.getElementById('add-image-upload').value = '';
    document.getElementById('add-image-preview').style.display = 'none';
  });

  // Image preview for edit form
  document.getElementById('edit-image-upload').addEventListener('change', function (e) {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        document.getElementById('edit-preview-image').src = event.target.result;
        document.getElementById('edit-image-preview').style.display = 'block';
      }
      reader.readAsDataURL(file);
    }
  });

  // Remove preview for edit form
  document.getElementById('edit-remove-preview').addEventListener('click', function () {
    document.getElementById('edit-image-upload').value = '';
    document.getElementById('edit-image-preview').style.display = 'none';
  });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/adminlayout.php';