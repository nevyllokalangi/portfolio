<?php
// Page Config
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

$pageTitle = 'User Management';
$pageCSS = '/public/css/admin/users.css';

// Base URL for redirects
$baseUrl = '/admin/user';

// Pagination settings
$usersPerPage = 10;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $usersPerPage;

// Get total number of users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPages = ceil($totalUsers / $usersPerPage);

// Get users for current page
$stmt = $pdo->prepare("SELECT id, username, email, role, is_active, created_at FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $usersPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
  if (!verify_csrf_token($_POST['csrf_token'])) {
    die('Invalid CSRF token');
  }

  $userId = (int) $_POST['user_id'];

  // Prevent deleting current user
  if ($userId !== $_SESSION['user_id']) {
    try {
      $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
      $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
      $stmt->execute();

      // Refresh the page to show updated list
      header("Location: " . $baseUrl . "?page=" . $currentPage);
      exit();
    } catch (PDOException $e) {
      $error = "Error deleting user: " . $e->getMessage();
    }
  } else {
    $error = "You cannot delete your own account.";
  }
}

// Handle edit action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
  if (!verify_csrf_token($_POST['csrf_token'])) {
    die('Invalid CSRF token');
  }

  $userId = (int) $_POST['user_id'];
  $newRole = isset($_POST['role']) ? sanitize_input($_POST['role']) : 'basic';
  $isActive = isset($_POST['is_active']) ? 1 : 0;

  // Prevent editing current user's role to basic
  if ($userId === $_SESSION['user_id'] && $newRole === ROLE_BASIC) {
    $error = "You cannot change your own role to basic.";
  } else {
    try {
      $stmt = $pdo->prepare("UPDATE users SET role = ?, is_active = ?, login_attempts = 0, locked_until = NULL WHERE id = ?");
      $stmt->execute([$newRole, $isActive, $userId]);

      $success = "User updated successfully.";
      header("Location: " . $baseUrl . "?page=" . $currentPage . "&success=1");
      exit();
    } catch (PDOException $e) {
      $error = "Error updating user: " . $e->getMessage();
    }
  }
}

ob_start();
?>
<main class="content">
  <div class="admin-header">
    <h1>User Management</h1>
    <p class="subtitle">Total Users: <?= $totalUsers ?></p>
  </div>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <div class="table-container">
    <table class="users-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
              <form method="POST" class="inline-form">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <select name="role" class="role-select" onchange="this.form.submit()">
                  <option value="basic" <?= $user['role'] === 'basic' ? 'selected' : '' ?>>Basic</option>
                  <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                  <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <input type="hidden" name="edit_user" value="1">
              </form>
            </td>
            <td>
              <form method="POST" class="inline-form">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <label class="status-toggle">
                  <input type="checkbox" name="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?>
                    onchange="this.form.submit()">
                  <span class="toggle-slider"></span>
                  <input type="hidden" name="edit_user" value="1">
                </label>
              </form>
            </td>
            <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
            <td>
              <form method="POST" class="delete-form"
                onsubmit="return confirm('Are you sure you want to delete this user?');">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <button type="submit" name="delete_user" class="btn-delete" title="Delete User">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path
                      d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                    <path fill-rule="evenodd"
                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                  </svg>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php if ($currentPage > 1): ?>
          <a href="?page=<?= $currentPage - 1 ?>" class="page-link">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?= $i ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
          <a href="?page=<?= $currentPage + 1 ?>" class="page-link">Next &raquo;</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</main>

<style>
  /* Admin Styles */
  .admin-container {
    width: 100vw;
    padding: 20px 5vw;
  }

  .admin-header {
    width: 100%;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
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

  /* Table Styles */
  .table-container {
    background: var(--secondary);
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .users-table {
    width: 100%;
    border-collapse: collapse;
  }

  .users-table th,
  .users-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--tertiary);
  }

  .users-table th {
    background-color: var(--tertiary);
    font-weight: 600;
    color: var(--primaryFont);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
  }

  .users-table tr:last-child td {
    border-bottom: none;
  }

  .users-table tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
  }

  /* Form Styles */
  .inline-form {
    display: inline-block;
    margin: 0;
  }

  /* Select Dropdown Styles */
  .role-select {
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid var(--secondary);
    background-color: var(--tertiary);
    color: var(--tertiaryFont);
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
  }

  /* Toggle Switch Styles */
  .status-toggle {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
  }

  .status-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e5e7eb;
    transition: .4s;
    border-radius: 24px;
  }

  .toggle-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }

  input:checked+.toggle-slider {
    background-color: #10b981;
  }

  input:checked+.toggle-slider:before {
    transform: translateX(26px);
  }

  /* Button Styles */
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

  /* Pagination Styles */
  .pagination {
    display: flex;
    justify-content: center;
    padding: 1.5rem;
    gap: 0.5rem;
  }

  .page-link {
    padding: 0.5rem 1rem;
    background-color: #edf2f7;
    color: #4a5568;
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 0.875rem;
  }

  .page-link:hover {
    background-color: #e2e8f0;
  }

  .page-link.active {
    background-color: #4299e1;
    color: white;
  }

  /* Alert Styles */
  .alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.375rem;
  }

  .alert-danger {
    background-color: #fee2e2;
    color: #b91c1c;
    border-left: 4px solid #dc2626;
  }
</style>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/adminlayout.php'; ?>