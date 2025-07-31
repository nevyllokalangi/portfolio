<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

$pdo = getPDO();
$pageTitle = 'Contact Messages';
$pageCSS = '';

// AJAX mark as read handler
if (isset($_POST['mark_read']) && is_numeric($_POST['mark_read'])) {
  $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
  $stmt->execute([':id' => $_POST['mark_read']]);
  exit;
}

// Delete message handler
if (isset($_POST['delete_message']) && is_numeric($_POST['delete_message'])) {
  $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
  $stmt->execute([':id' => $_POST['delete_message']]);
  exit;
}

// Mark all as read handler
if (isset($_POST['mark_all_read'])) {
  $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1");
  $stmt->execute();
  exit;
}

// Get all messages
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<main class="content">
  <div className="wrapper">
    <div class="admin-header">
      <h1>Contact Messages</h1>
      <div class="header-actions">
        <button id="markAllReadBtn" class="button">Mark All as Read</button>
        <button id="toggleUnreadBtn" class="button">Show Unread Only</button>
      </div>
    </div>

    <div class="stats-container">
      <div class="stat-card">
        <span class="stat-value"><?= count($messages) ?></span>
        <span class="stat-label">Total Messages</span>
      </div>
      <div class="stat-card">
        <?php
        $unreadCount = count(array_filter($messages, fn($msg) => (int) $msg['is_read'] === 0));
        ?>
        <span class="stat-value"><?= $unreadCount ?></span>
        <span class="stat-label">Unread Messages</span>
      </div>
      <div class="stat-card">
        <span class="stat-value"><?= count($messages) > 0 ? round($unreadCount / count($messages) * 100) : 0 ?>%</span>
        <span class="stat-label">Unread Rate</span>
      </div>
    </div>

    <div class="table-container">
      <table class="messages-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($messages as $msg): ?>
            <tr class="<?= (int) $msg['is_read'] === 0 ? 'unread' : '' ?>"
              data-status="<?= (int) $msg['is_read'] === 0 ? 'unread' : 'read' ?>">
              <td><?= htmlspecialchars($msg['created_at']) ?></td>
              <td><?= htmlspecialchars($msg['name']) ?></td>
              <td><?= htmlspecialchars($msg['email']) ?></td>
              <td>
                <span class="status-badge <?= (int) $msg['is_read'] === 0 ? 'unread-badge' : 'read-badge' ?>">
                  <?= (int) $msg['is_read'] === 0 ? 'Unread' : 'Read' ?>
                </span>
              </td>
              <td class="actions">
                <button class="view-btn" data-msg='<?= json_encode($msg) ?>'>View</button>
                <button class="delete-btn" data-id="<?= $msg['id'] ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path
                      d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                    <path fill-rule="evenodd"
                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                  </svg>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- View Modal -->
<div class="modal" id="messageModal" style="display:none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Message Details</h2>
      <button class="close-modal close-btn">&times;</button>
    </div>

    <div class="modal-body">
      <div class="form-row">
        <div class="form-group">
          <label><strong>Date</strong></label>
          <p id="modal-date"></p>
        </div>
        <div class="form-group">
          <label><strong>Name</strong></label>
          <p id="modal-name"></p>
        </div>
        <div class="form-group">
          <label><strong>Email</strong></label>
          <p id="modal-email"></p>
        </div>
        <div class="form-group">
          <label><strong>Status</strong></label>
          <p id="modal-status"></p>
        </div>
        <div class="form-group full">
          <label><strong>Message</strong></label>
          <div class="message-box" id="modal-message"></div>
        </div>
      </div>
    </div>

    <div class="modal-actions">
      <button type="button" class="button btn-danger close-modal">Close</button>
      <button type="button" class="button" id="deleteInModal">Delete Message</button>
    </div>
  </div>
</div>

<!-- Confirmation Modal -->
<div class="modal" id="confirmationModal" style="display:none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Confirm Deletion</h2>
      <button class="close-modal close-btn">&times;</button>
    </div>

    <div class="modal-body">
      <p>Are you sure you want to delete this message? This action cannot be undone.</p>
    </div>

    <div class="modal-actions">
      <button type="button" class="button btn-cancel close-modal">Cancel</button>
      <button type="button" class="button btn-danger" id="confirmDelete">Delete</button>
    </div>
  </div>
</div>

<style>
  .wrapper {
    margin: 0 auto;
    width: 90%;
  }

  .admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--color-border);
  }

  .admin-header h1 {
    font-size: 2rem;
    font-weight: 600;
    margin: 0;
  }

  .header-actions {
    display: flex;
    gap: 0.75rem;
  }

  .button {
    padding: 0.5rem 1rem;
    background: var(--color-accent-primary);
    color: var(--color-bg-primary);
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s ease;
  }

  .button:hover {
    background: var(--color-accent-hover);
  }

  .btn-danger {
    background: var(--color-error);
  }

  .btn-danger:hover {
    background: var(--color-error);
    opacity: 0.85;
  }

  .btn-cancel {
    background: var(--color-border);
  }

  .btn-cancel:hover {
    background: var(--color-bg-secondary);
  }

  .stats-container {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .stat-card {
    flex: 1;
    background: var(--color-bg-secondary);
    border-radius: 0.5rem;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .stat-value {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
  }

  .stat-label {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
  }

  .table-container {
    background: var(--color-bg-secondary);
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .messages-table {
    width: 100%;
    border-collapse: collapse;
  }

  .messages-table th,
  .messages-table td {
    padding: 1rem;
    text-align: left;
  }

  .messages-table th {
    background-color: var(--color-tertiary);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
  }

  .messages-table tbody tr {
    border-bottom: 1px solid var(--color-border);
  }

  .messages-table tbody tr:last-child {
    border-bottom: none;
  }

  .messages-table tbody tr.unread {
    background-color: var(--color-accent-primary);
    font-weight: 600;
  }

  .status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .unread-badge {
    background-color: var(--color-accent-primary);
    color: var(--color-bg-primary);
  }

  .read-badge {
    background-color: var(--color-success);
    color: var(--color-bg-primary);
  }

  .actions {
    display: flex;
    gap: 0.5rem;
  }

  .view-btn {
    padding: 0.4rem 0.7rem;
    background: var(--color-accent-primary);
    color: var(--color-bg-primary);
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .delete-btn {
    padding: 0.4rem 0.7rem;
    background: var(--color-error);
    color: var(--color-bg-primary);
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .delete-btn:hover {
    background: var(--color-error);
    opacity: 0.85;
  }

  .modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 999;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-content {
    background: var(--color-bg-primary);
    max-width: 600px;
    width: 90%;
    padding: 1.5rem;
    border-radius: 0.5rem;
    position: relative;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--color-border);
  }

  .close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--color-text-secondary);
  }

  .close-modal:hover {
    color: var(--color-accent-hover);
  }

  .form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .form-group {
    flex: 1 1 45%;
  }

  .form-group.full {
    flex: 1 1 100%;
    max-width: 100%;
  }

  .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--color-text-primary);
  }

  .form-group p {
    margin: 0;
    padding: 0.5rem 0;
    color: var(--color-text-primary);
  }

  .message-box {
    white-space: pre-wrap;
    word-break: break-word;
    background: var(--color-bg-secondary);
    padding: 1em;
    border: 1px solid var(--color-border);
    border-radius: 0.5rem;
    line-height: 1.6;
    max-height: 300px;
    overflow-y: auto;
  }

  .modal-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    gap: 0.75rem;
  }

  .no-messages {
    text-align: center;
    padding: 2rem;
    color: var(--color-text-secondary);
    font-style: italic;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // View message modal
    document.querySelectorAll('.view-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const msg = JSON.parse(this.dataset.msg);

        document.getElementById('modal-date').textContent = msg.created_at;
        document.getElementById('modal-name').textContent = msg.name;
        document.getElementById('modal-email').textContent = msg.email;
        document.getElementById('modal-status').textContent = msg.is_read == 0 ? 'Unread' : 'Read';
        document.getElementById('modal-message').textContent = msg.message;

        // Set data attribute for deletion in modal
        document.getElementById('deleteInModal').dataset.id = msg.id;

        document.getElementById('messageModal').style.display = 'flex';

        // Mark as read if unread
        if (msg.is_read == 0) {
          fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'mark_read=' + msg.id
          });

          // Update UI
          const row = this.closest('tr');
          row.classList.remove('unread');
          row.querySelector('.status-badge').className = 'status-badge read-badge';
          row.querySelector('.status-badge').textContent = 'Read';

          // Update unread count
          const unreadBadge = document.querySelector('.unread-badge');
          if (unreadBadge) {
            const unreadCount = parseInt(document.querySelector('.stat-value:nth-child(2)').textContent);
            document.querySelector('.stat-value:nth-child(2)').textContent = unreadCount - 1;
            document.querySelector('.stat-value:nth-child(3)').textContent =
              Math.round((unreadCount - 1) / <?= count($messages) ?> * 100) + '%';
          }
        }
      });
    });

    // Delete button handler
    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const row = this.closest('tr');
        const isUnread = row.classList.contains('unread');

        // Show confirmation modal
        document.getElementById('confirmationModal').style.display = 'flex';

        // Set up delete confirmation
        document.getElementById('confirmDelete').onclick = function () {
          fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'delete_message=' + id
          }).then(() => {
            // Remove row from table
            row.remove();

            // Update stats
            const totalCount = parseInt(document.querySelector('.stat-value:first-child').textContent);
            document.querySelector('.stat-value:first-child').textContent = totalCount - 1;

            if (isUnread) {
              const unreadCount = parseInt(document.querySelector('.stat-value:nth-child(2)').textContent);
              document.querySelector('.stat-value:nth-child(2)').textContent = unreadCount - 1;
            }

            // Update unread rate
            const newTotal = totalCount - 1;
            const newUnread = isUnread ? unreadCount - 1 : unreadCount;
            const unreadRate = newTotal > 0 ? Math.round(newUnread / newTotal * 100) : 0;
            document.querySelector('.stat-value:nth-child(3)').textContent = unreadRate + '%';

            // Close modal
            document.getElementById('confirmationModal').style.display = 'none';

            // If no messages left, show empty state
            if (newTotal === 0) {
              document.querySelector('tbody').innerHTML = `
                <tr>
                  <td colspan="5" class="no-messages">No messages found</td>
                </tr>
              `;
            }
          });
        };
      });
    });

    // Delete from modal
    document.getElementById('deleteInModal').addEventListener('click', function () {
      const id = this.dataset.id;
      const row = document.querySelector(`.delete-btn[data-id="${id}"]`).closest('tr');
      const isUnread = row.classList.contains('unread');

      document.getElementById('messageModal').style.display = 'none';
      document.getElementById('confirmationModal').style.display = 'flex';

      document.getElementById('confirmDelete').onclick = function () {
        fetch('', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'delete_message=' + id
        }).then(() => {
          row.remove();

          // Update stats
          const totalCount = parseInt(document.querySelector('.stat-value:first-child').textContent);
          document.querySelector('.stat-value:first-child').textContent = totalCount - 1;

          if (isUnread) {
            const unreadCount = parseInt(document.querySelector('.stat-value:nth-child(2)').textContent);
            document.querySelector('.stat-value:nth-child(2)').textContent = unreadCount - 1;
          }

          // Update unread rate
          const newTotal = totalCount - 1;
          const newUnread = isUnread ? unreadCount - 1 : unreadCount;
          const unreadRate = newTotal > 0 ? Math.round(newUnread / newTotal * 100) : 0;
          document.querySelector('.stat-value:nth-child(3)').textContent = unreadRate + '%';

          document.getElementById('confirmationModal').style.display = 'none';

          // If no messages left, show empty state
          if (newTotal === 0) {
            document.querySelector('tbody').innerHTML = `
              <tr>
                <td colspan="5" class="no-messages">No messages found</td>
              </tr>
            `;
          }
        });
      };
    });

    // Mark all as read
    document.getElementById('markAllReadBtn').addEventListener('click', function () {
      fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'mark_all_read=1'
      }).then(() => {
        // Update UI
        document.querySelectorAll('.unread').forEach(row => {
          row.classList.remove('unread');
          const statusBadge = row.querySelector('.status-badge');
          statusBadge.className = 'status-badge read-badge';
          statusBadge.textContent = 'Read';
        });

        // Update stats
        const unreadCount = document.querySelector('.stat-value:nth-child(2)');
        unreadCount.textContent = '0';
        document.querySelector('.stat-value:nth-child(3)').textContent = '0%';
      });
    });

    // Toggle unread only
    let showAll = true;
    document.getElementById('toggleUnreadBtn').addEventListener('click', function () {
      showAll = !showAll;
      this.textContent = showAll ? 'Show Unread Only' : 'Show All Messages';

      document.querySelectorAll('tbody tr').forEach(row => {
        if (showAll) {
          row.style.display = '';
        } else {
          row.style.display = row.dataset.status === 'unread' ? '' : 'none';
        }
      });
    });

    // Close modals
    document.querySelectorAll('.close-modal').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('messageModal').style.display = 'none';
        document.getElementById('confirmationModal').style.display = 'none';
      });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function (e) {
      if (e.target.classList.contains('modal')) {
        document.getElementById('messageModal').style.display = 'none';
        document.getElementById('confirmationModal').style.display = 'none';
      }
    });
  });
</script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/adminlayout.php'; ?>