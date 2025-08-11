<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Handle image upload with validation
  function handleImageUpload()
  {
    $uploadDir = __DIR__ . '/../../../public/uploads/project/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $fileType = finfo_file($finfo, $_FILES['featured_image']['tmp_name']);
      finfo_close($finfo);

      if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.'];
      }

      $maxSize = 4 * 1024 * 1024; // 4MB
      if ($_FILES['featured_image']['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size exceeds 4MB limit.'];
      }

      // Generate unique filename
      $extension = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
      $filename = uniqid() . '.' . $extension;
      $targetPath = $uploadDir . $filename;

      if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => '/public/uploads/project/' . $filename];
      }
    }
    return ['success' => false, 'path' => null, 'message' => 'No file uploaded or upload error'];
  }

  // Add new project
  if (isset($_POST['add_project'])) {
    $featuredImage = null;
    if (!empty($_FILES['featured_image']['name'])) {
      $imageResult = handleImageUpload();
      if (!$imageResult['success']) {
        $_SESSION['error_message'] = $imageResult['message'] ?? 'Image upload failed';
        header("Location: ../admin/project");
        exit();
      }
      $featuredImage = $imageResult['path'];
    }

    // Handle author data
    $authorNames = $_POST['author_name'] ?? [];
    $authorAvatars = $_POST['author_avatar'] ?? [];

    // Combine author data into comma-separated strings
    $authorNameStr = implode(',', array_map('htmlspecialchars', $authorNames));
    $authorAvatarStr = implode(',', array_map('htmlspecialchars', $authorAvatars));

    try {
      $stmt = $pdo->prepare("INSERT INTO projects 
                (title, date, excerpt, content, featured_image, author_name, author_avatar) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([
        htmlspecialchars(trim($_POST['title'])),
        htmlspecialchars(trim($_POST['date'])),
        htmlspecialchars(trim($_POST['excerpt'])),
        htmlspecialchars(trim($_POST['content'])),
        $featuredImage,
        $authorNameStr,
        $authorAvatarStr
      ]);
    } catch (Exception $e) {
      // Delete uploaded file if insert fails
      if ($featuredImage) {
        $filePath = __DIR__ . '/../../../public' . $featuredImage;
        if (file_exists($filePath) && is_file($filePath)) {
          unlink($filePath);
        }
      }
      $_SESSION['error_message'] = "Database error: " . $e->getMessage();
      header("Location: ../admin/project");
      exit();
    }

    header("Location: ../admin/project");
    exit();
  }
  // Update existing project
  elseif (isset($_POST['update_project'])) {
    $id = (int) $_POST['id'];
    $currentImagePath = $_POST['current_featured_image'] ?? '';
    $newImagePath = $currentImagePath;

    // Handle image removal
    if (isset($_POST['remove_featured_image']) && $_POST['remove_featured_image'] == '1') {
      if ($currentImagePath) {
        $filePath = __DIR__ . '/../../../public' . $currentImagePath;
        if (file_exists($filePath) && is_file($filePath)) {
          if (!unlink($filePath)) {
            $_SESSION['error_message'] = "Failed to remove existing image";
            header("Location: ../admin/project");
            exit();
          }
        }
      }
      $newImagePath = '';
    }
    // Handle new image upload
    elseif (!empty($_FILES['featured_image']['name'])) {
      $imageResult = handleImageUpload();
      if (!$imageResult['success']) {
        $_SESSION['error_message'] = $imageResult['message'] ?? 'Image upload failed';
        header("Location: ../admin/project");
        exit();
      }

      // Delete old image BEFORE setting new path
      if ($currentImagePath) {
        $oldFilePath = __DIR__ . '/../../../public' . $currentImagePath;
        if (file_exists($oldFilePath) && is_file($oldFilePath)) {
          if (!unlink($oldFilePath)) {
            $_SESSION['error_message'] = "Failed to replace existing image";
            header("Location: ../admin/project");
            exit();
          }
        }
      }
      $newImagePath = $imageResult['path'];
    }

    // Handle author data
    $authorNames = $_POST['author_name'] ?? [];
    $authorAvatars = $_POST['author_avatar'] ?? [];

    // Combine author data into comma-separated strings
    $authorNameStr = implode(',', array_map('htmlspecialchars', $authorNames));
    $authorAvatarStr = implode(',', array_map('htmlspecialchars', $authorAvatars));

    try {
      $stmt = $pdo->prepare("UPDATE projects SET 
                title = ?, 
                date = ?, 
                excerpt = ?, 
                content = ?, 
                featured_image = ?,
                author_name = ?,
                author_avatar = ?
                WHERE id = ?");
      $stmt->execute([
        htmlspecialchars(trim($_POST['title'])),
        htmlspecialchars(trim($_POST['date'])),
        htmlspecialchars(trim($_POST['excerpt'])),
        htmlspecialchars(trim($_POST['content'])),
        $newImagePath,
        $authorNameStr,
        $authorAvatarStr,
        $id
      ]);
    } catch (Exception $e) {
      $_SESSION['error_message'] = "Database error: " . $e->getMessage();
      header("Location: ../admin/project");
      exit();
    }

    header("Location: ../admin/project");
    exit();
  }
  // Delete project
  elseif (isset($_POST['delete_project'])) {
    $id = (int) $_POST['id'];

    try {
      $stmt = $pdo->prepare("SELECT featured_image FROM projects WHERE id = ?");
      $stmt->execute([$id]);
      $imagePath = $stmt->fetchColumn();

      // Delete file if exists
      if ($imagePath) {
        $filePath = __DIR__ . '/../../../public' . $imagePath;
        if (file_exists($filePath) && is_file($filePath)) {
          if (!unlink($filePath)) {
            $_SESSION['error_message'] = "Failed to delete image file";
            header("Location: ../admin/project");
            exit();
          }
        }
      }

      // Delete database entry
      $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
      $stmt->execute([$id]);

    } catch (Exception $e) {
      $_SESSION['error_message'] = "Delete failed: " . $e->getMessage();
      header("Location: ../admin/project");
      exit();
    }

    header("Location: ../admin/project");
    exit();
  }
}

// Fetch existing projects
try {
  $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY date DESC");
  $stmt->execute();
  $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  $projects = [];
  $_SESSION['error_message'] = "Failed to load projects: " . $e->getMessage();
}

// Page configuration
$pageTitle = 'Manage Projects';
ob_start();
?>

<style>
  .admin-container {
    width: 90%;
    margin: 0 auto;
    padding: 20px 0;
    position: relative;
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

  .projects-table {
    width: 100%;
    border-collapse: collapse;
  }

  .projects-table th,
  .projects-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--tertiary);
  }

  .projects-table th {
    background-color: var(--tertiary);
    font-weight: 600;
    color: var(--primaryFont);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
  }

  .projects-table tr:last-child td {
    border-bottom: none;
  }

  .projects-table tr:hover {
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
    max-width: 800px;
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

  /* Author management styles */
  .authors-container {
    margin: 15px 0;
  }

  .author-entry {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
  }

  .author-entry input {
    flex: 1;
  }

  .add-author-btn {
    margin-top: 10px;
  }

  .remove-author-btn {
    background: #fee2e2;
    color: #b91c1c;
    border: none;
    border-radius: 4px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }

  .remove-author-btn:hover {
    background: #fecaca;
  }
</style>

<div class="admin-container">
  <div class="admin-header">
    <div>
      <h1>Manage Projects</h1>
      <p class="subtitle">Total Projects: <?= count($projects) ?></p>
    </div>
    <button class="button btn-primary add-entry-button" id="open-add-modal">
      <span>ADD PROJECT</span>
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
        <path
          d="M440-440H240q-17 0-28.5-11.5T200-480q0-17 11.5-28.5T240-520h200v-200q0-17 11.5-28.5T480-760q17 0 28.5 11.5T520-720v200h200q17 0 28.5 11.5T760-480q0 17-11.5 28.5T720-440H520v200q0 17-11.5 28.5T480-200q-17 0-28.5-11.5T440-240v-200Z" />
      </svg>
    </button>
  </div>

  <!-- Projects Table -->
  <div class="table-container">
    <?php if (count($projects) > 0): ?>
      <table class="projects-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Featured Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($projects as $project): ?>
            <tr>
              <td><?= htmlspecialchars($project['title']) ?></td>
              <td><?= date('F j, Y', strtotime($project['date'])) ?></td>
              <td>
                <?php if (!empty($project['featured_image'])): ?>
                  <img src="<?= htmlspecialchars($project['featured_image']) ?>" alt="Project image"
                    style="max-width: 50px; max-height: 50px; border-radius: 4px;">
                <?php else: ?>
                  <span style="color: #999;">No image</span>
                <?php endif; ?>
              </td>
              <td class="action-cell">
                <button class="button btn-secondary btn-sm edit-project" data-id="<?= $project['id'] ?>"
                  data-title="<?= htmlspecialchars($project['title']) ?>"
                  data-date="<?= htmlspecialchars($project['date']) ?>"
                  data-excerpt="<?= htmlspecialchars($project['excerpt']) ?>"
                  data-content="<?= htmlspecialchars($project['content']) ?>"
                  data-featured-image="<?= htmlspecialchars($project['featured_image']) ?>"
                  data-author-name="<?= htmlspecialchars($project['author_name']) ?>"
                  data-author-avatar="<?= htmlspecialchars($project['author_avatar']) ?>">
                  <span>EDIT</span><svg xmlns="http://www.w3.org/2000/svg" height="12px" viewBox="0 -960 960 960"
                    width="24px" fill="#ffffe3">
                    <path
                      d="M160-120q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm544-528 56-56-56-56-56 56 56 56Z" />
                  </svg>
                </button>

                <form method="POST" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $project['id'] ?>">
                  <button type="submit" name="delete_project" class="button btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this project?')">
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
        No projects found. Add your first project using the button above.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add Modal -->
<div class="modal" id="add-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Add New Project</h2>
      <button class="close-modal">&times;</button>
    </div>

    <form method="POST" id="add-form" enctype="multipart/form-data">
      <input type="hidden" name="add_project" value="1">

      <div class="form-group">
        <label>Project Title</label>
        <input type="text" name="title" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="date" required>
        </div>
      </div>

      <div class="form-group">
        <label>Excerpt (Short Description)</label>
        <textarea name="excerpt" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label>Content</label>
        <textarea name="content" rows="6" required></textarea>
      </div>

      <div class="form-group">
        <label>Featured Image</label>
        <div class="file-input-container">
          <input type="file" name="featured_image" id="add-featured-image" accept="image/*">
          <label class="file-input-label" for="add-featured-image">
            Choose an image (JPG, PNG, GIF, max 4MB)
          </label>
        </div>
        <div class="image-preview-container" id="add-featured-image-preview" style="display: none;">
          <img src="" alt="Featured image preview" class="image-preview" id="add-preview-featured-image">
          <button type="button" class="button btn-danger btn-sm" id="add-remove-featured-preview">
            Remove Selected Image
          </button>
        </div>
      </div>

      <div class="form-group">
        <label>Authors</label>
        <div class="authors-container" id="add-authors-container">
          <div class="author-entry">
            <input type="text" name="author_name[]" placeholder="Author Name" required>
            <input type="text" name="author_avatar[]" placeholder="Avatar URL" required>
            <button type="button" class="remove-author-btn" onclick="this.parentElement.remove()">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </div>
        <button type="button" class="button btn-secondary btn-sm add-author-btn" id="add-author-btn">
          Add Another Author
        </button>
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
          <span>ADD PROJECT</span>
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
      <h2>Edit Project</h2>
      <button class="close-modal">&times;</button>
    </div>

    <form method="POST" id="edit-form" enctype="multipart/form-data">
      <input type="hidden" name="id" id="edit-id">
      <input type="hidden" name="update_project" value="1">
      <input type="hidden" name="current_featured_image" id="edit-current-featured-image">

      <div class="form-group">
        <label>Project Title</label>
        <input type="text" name="title" id="edit-title" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="date" id="edit-date" required>
        </div>
      </div>

      <div class="form-group">
        <label>Excerpt (Short Description)</label>
        <textarea name="excerpt" id="edit-excerpt" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label>Content</label>
        <textarea name="content" id="edit-content" rows="6" required></textarea>
      </div>

      <div class="form-group">
        <label>Featured Image</label>

        <!-- Current image preview -->
        <div class="image-preview-container" id="current-featured-image-container">
          <img src="" alt="Current featured image" class="image-preview" id="current-featured-image">
          <div class="remove-image-container" id="remove-featured-image-box">
            <label>
              <input type="checkbox" name="remove_featured_image" value="1">
              Remove current image
            </label>
          </div>
        </div>

        <!-- New image upload -->
        <div class="file-input-container">
          <input type="file" name="featured_image" id="edit-featured-image" accept="image/*">
          <label class="file-input-label" for="edit-featured-image">
            Upload new image (JPG, PNG, GIF, max 4MB)
          </label>
        </div>
        <div class="image-preview-container" id="edit-featured-image-preview" style="display: none;">
          <img src="" alt="New featured image preview" class="image-preview" id="edit-preview-featured-image">
          <button type="button" class="button btn-danger btn-sm" id="edit-remove-featured-preview">
            Remove New Image
          </button>
        </div>
      </div>

      <div class="form-group">
        <label>Authors</label>
        <div class="authors-container" id="edit-authors-container">
          <!-- Authors will be added here dynamically -->
        </div>
        <button type="button" class="button btn-secondary btn-sm add-author-btn" id="edit-add-author-btn">
          Add Another Author
        </button>
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
          <span>UPDATE PROJECT</span>
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
    document.getElementById('add-featured-image-preview').style.display = 'none';
    // Reset authors to one empty field
    const container = document.getElementById('add-authors-container');
    container.innerHTML = `
      <div class="author-entry">
        <input type="text" name="author_name[]" placeholder="Author Name" required>
        <input type="text" name="author_avatar[]" placeholder="Avatar URL" required>
        <button type="button" class="remove-author-btn" onclick="this.parentElement.remove()">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
    `;
    openModal('add-modal');
  });

  // Add author button functionality
  document.getElementById('add-author-btn').addEventListener('click', () => {
    const container = document.getElementById('add-authors-container');
    const newAuthor = document.createElement('div');
    newAuthor.className = 'author-entry';
    newAuthor.innerHTML = `
      <input type="text" name="author_name[]" placeholder="Author Name" required>
      <input type="text" name="author_avatar[]" placeholder="Avatar URL" required>
      <button type="button" class="remove-author-btn" onclick="this.parentElement.remove()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    `;
    container.appendChild(newAuthor);
  });

  // Edit modal functionality
  const editButtons = document.querySelectorAll('.edit-project');

  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      const id = button.dataset.id;
      const title = button.dataset.title;
      const date = button.dataset.date;
      const excerpt = button.dataset.excerpt;
      const content = button.dataset.content;
      const featuredImage = button.dataset.featuredImage;
      const authorNames = button.dataset.authorName.split(',');
      const authorAvatars = button.dataset.authorAvatar.split(',');

      // Set basic fields
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-title').value = title;
      document.getElementById('edit-date').value = date;
      document.getElementById('edit-excerpt').value = excerpt;
      document.getElementById('edit-content').value = content;
      document.getElementById('edit-current-featured-image').value = featuredImage;

      // Handle featured image preview
      const currentImageContainer = document.getElementById('current-featured-image-container');
      const currentImage = document.getElementById('current-featured-image');
      const removeImageBox = document.getElementById('remove-featured-image-box');

      if (featuredImage) {
        currentImage.src = featuredImage;
        currentImageContainer.style.display = 'block';
        removeImageBox.style.display = 'block';
      } else {
        currentImageContainer.style.display = 'none';
        removeImageBox.style.display = 'none';
      }

      // Reset new image preview
      document.getElementById('edit-featured-image-preview').style.display = 'none';
      document.getElementById('edit-featured-image').value = '';
      document.querySelector('input[name="remove_featured_image"]').checked = false;

      // Handle authors
      const authorsContainer = document.getElementById('edit-authors-container');
      authorsContainer.innerHTML = '';

      authorNames.forEach((name, index) => {
        if (name.trim() === '') return;

        const newAuthor = document.createElement('div');
        newAuthor.className = 'author-entry';
        newAuthor.innerHTML = `
          <input type="text" name="author_name[]" placeholder="Author Name" value="${name}" required>
          <input type="text" name="author_avatar[]" placeholder="Avatar URL" value="${authorAvatars[index] || ''}" required>
          <button type="button" class="remove-author-btn" onclick="this.parentElement.remove()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        `;
        authorsContainer.appendChild(newAuthor);
      });

      // Add empty author field if none exist
      if (authorsContainer.children.length === 0) {
        const newAuthor = document.createElement('div');
        newAuthor.className = 'author-entry';
        newAuthor.innerHTML = `
          <input type="text" name="author_name[]" placeholder="Author Name" required>
          <input type="text" name="author_avatar[]" placeholder="Avatar URL" required>
          <button type="button" class="remove-author-btn" onclick="this.parentElement.remove()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        `;
        authorsContainer.appendChild(newAuthor);
      }

      // Add author button functionality for edit modal
      document.getElementById('edit-add-author-btn').addEventListener('click', () => {
        const newAuthor = document.createElement('div');
        newAuthor.className = 'author-entry';
        newAuthor.innerHTML = `
          <input type="text" name="author_name[]" placeholder="Author Name" required>
          <input type="text" name="author_avatar[]" placeholder="Avatar URL" required>
          <button type="button" class="remove-author-btn" onclick="this.parentElement.remove()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        `;
        authorsContainer.appendChild(newAuthor);
      });

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
  document.getElementById('add-featured-image').addEventListener('change', function (e) {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        document.getElementById('add-preview-featured-image').src = event.target.result;
        document.getElementById('add-featured-image-preview').style.display = 'block';
      }
      reader.readAsDataURL(file);
    }
  });

  // Remove preview for add form
  document.getElementById('add-remove-featured-preview').addEventListener('click', function () {
    document.getElementById('add-featured-image').value = '';
    document.getElementById('add-featured-image-preview').style.display = 'none';
  });

  // Image preview for edit form
  document.getElementById('edit-featured-image').addEventListener('change', function (e) {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        document.getElementById('edit-preview-featured-image').src = event.target.result;
        document.getElementById('edit-featured-image-preview').style.display = 'block';
      }
      reader.readAsDataURL(file);
    }
  });

  // Remove preview for edit form
  document.getElementById('edit-remove-featured-preview').addEventListener('click', function () {
    document.getElementById('edit-featured-image').value = '';
    document.getElementById('edit-featured-image-preview').style.display = 'none';
  });

  // Remove image checkbox effect
  document.querySelector('input[name="remove_featured_image"]')?.addEventListener('change', function () {
    if (this.checked) {
      document.getElementById('current-featured-image').style.opacity = '0.5';
    } else {
      document.getElementById('current-featured-image').style.opacity = '1';
    }
  });

  // Edit form image replacement confirmation
  document.getElementById('edit-form').addEventListener('submit', function (e) {
    const currentImagePath = document.getElementById('edit-current-featured-image').value;
    const fileInput = this.querySelector('input[type="file"]');
    const removeImageChecked = document.querySelector('input[name="remove_featured_image"]').checked;

    // Only ask for confirmation when replacing existing image
    if (currentImagePath && currentImagePath.trim() !== '' && fileInput.files.length > 0 && !removeImageChecked) {
      if (!confirm('Are you sure you want to replace the existing image?')) {
        e.preventDefault();
        fileInput.value = ''; // clear the file input
        return false;
      }
    }
  });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/adminlayout.php';
?>