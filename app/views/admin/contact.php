<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

$pageTitle = 'Contact Messages';
$pageCSS = '';

$pdo = getPDO();

// Get messages from database
try {
  $stmt = $pdo->query("SELECT * FROM contact_messages WHERE is_spam = 0 ORDER BY created_at DESC");
  $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching messages: " . $e->getMessage());
}

ob_start();
?>
<main class="content">
  <div class="admin-header">
    <h1>Contact Messages</h1>
  </div>

  <div class="table-container">
    <table class="messages-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Name</th>
          <th>Email</th>
          <th>Subject</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $message): ?>
          <tr class="<?= $message['is_read'] ? '' : 'unread' ?>">
            <td><?= htmlspecialchars($message['created_at']) ?></td>
            <td><?= htmlspecialchars($message['name']) ?></td>
            <td><?= htmlspecialchars($message['email']) ?></td>
            <td><?= htmlspecialchars($message['subject']) ?></td>
            <td>
              <a href="view_message.php?id=<?= $message['id'] ?>">View</a> |
              <a href="mark_spam.php?id=<?= $message['id'] ?>" onclick="return confirm('Mark as spam?')">Spam</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<style>
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

  .table-container {
    background: var(--secondary);
    border-radius: 0.5rem;
    overflow: hidden;
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
    background-color: var(--tertiary);
    font-weight: 600;
    color: var(--primaryFont);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
  }

  .messages-table tr:hover {
    background-color: var(--primary);
  }

  .unread {
    font-weight: bold;
  }

  .messages-table a {
    color: var(--accentBlue);
    text-decoration: none;
  }

  .messages-table a:hover {
    text-decoration: underline;
  }
</style>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/adminlayout.php'; ?>