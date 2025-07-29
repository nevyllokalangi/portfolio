<?php
require_once __DIR__ . '/../../../config.php';
require_login();
require_role(ROLE_ADMIN);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Add new FAQ entry
  if (isset($_POST['add_faq'])) {
    try {
      $stmt = $pdo->prepare("INSERT INTO faqs (question, answer, display_order) VALUES (?, ?, ?)");
      $stmt->execute([
        htmlspecialchars(trim($_POST['question'])),
        htmlspecialchars(trim($_POST['answer'])),
        (int) $_POST['display_order']
      ]);
    } catch (Exception $e) {
      $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
    header("Location: ../admin/faq");
    exit();
  }
  // Update existing entry
  elseif (isset($_POST['update_faq'])) {
    try {
      $stmt = $pdo->prepare("UPDATE faqs SET question=?, answer=?, display_order=? WHERE id=?");
      $stmt->execute([
        htmlspecialchars(trim($_POST['question'])),
        htmlspecialchars(trim($_POST['answer'])),
        (int) $_POST['display_order'],
        (int) $_POST['id']
      ]);
    } catch (Exception $e) {
      $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
    header("Location: ../admin/faq");
    exit();
  }
  // Delete entry
  elseif (isset($_POST['delete_faq'])) {
    $id = (int) $_POST['id'];
    try {
      $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = ?");
      $stmt->execute([$id]);
    } catch (Exception $e) {
      $_SESSION['error_message'] = "Delete failed: " . $e->getMessage();
    }
    header("Location: ../admin/faq");
    exit();
  }
  // Handle drag-and-drop reordering
  elseif (isset($_POST['reorder_faqs'])) {
    $newOrder = json_decode($_POST['new_order'], true);

    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare("UPDATE faqs SET display_order = ? WHERE id = ?");

      foreach ($newOrder as $index => $faq) {
        $displayOrder = $index + 1;
        $stmt->execute([$displayOrder, $faq['id']]);
      }

      $pdo->commit();
      echo json_encode(['success' => true]);
    } catch (Exception $e) {
      $pdo->rollBack();
      http_response_code(500);
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit();
  }
}

// Fetch existing entries
try {
  $stmt = $pdo->prepare("SELECT * FROM faqs ORDER BY display_order ASC");
  $stmt->execute();
  $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  $faqs = [];
  $_SESSION['error_message'] = "Failed to load FAQs: " . $e->getMessage();
}

// Page configuration
$pageTitle = 'Manage FAQs';
ob_start();
?>

<style>
  /* Reuse the same styles from Biography.php */
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

  .faq-table {
    width: 100%;
    border-collapse: collapse;
  }

  .faq-table th,
  .faq-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--tertiary);
  }

  .faq-table th {
    background-color: var(--tertiary);
    font-weight: 600;
    color: var(--primaryFont);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
  }

  .faq-table tr:last-child td {
    border-bottom: none;
  }

  .faq-table tr {
    cursor: move;
    /* Indicates draggable rows */
    transition: all 0.2s;
  }

  .faq-table tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
  }

  .faq-table tr.dragging {
    opacity: 0.5;
    background-color: rgba(0, 0, 255, 0.05);
  }

  .faq-table tr.drop-over-above {
    border-top: 2px solid rgb(85, 131, 241);
  }

  .faq-table tr.drop-over-below {
    border-bottom: 2px solid rgb(85, 131, 241);
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

  .drag-handle {
    cursor: move;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primaryFont);
    opacity: 0.7;
    transition: opacity 0.2s;
  }

  .drag-handle:hover {
    opacity: 1;
  }
</style>

<div class="admin-container">
  <div class="admin-header">
    <div>
      <h1>Manage FAQ Entries</h1>
      <p class="subtitle">Total Entries: <?= count($faqs) ?></p>
    </div>
    <button class="button btn-primary add-entry-button" id="open-add-modal">
      <span>ADD</span>
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffe3">
        <path
          d="M440-440H240q-17 0-28.5-11.5T200-480q0-17 11.5-28.5T240-520h200v-200q0-17 11.5-28.5T480-760q17 0 28.5 11.5T520-720v200h200q17 0 28.5 11.5T760-480q0 17-11.5 28.5T720-440H520v200q0 17-11.5 28.5T480-200q-17 0-28.5-11.5T440-240v-200Z" />
      </svg>
    </button>
  </div>

  <!-- FAQ Table -->
  <div class="table-container">
    <?php if (count($faqs) > 0): ?>
      <table class="faq-table" id="faq-table">
        <thead>
          <tr>
            <th></th>
            <th>Question</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="faq-table-body">
          <?php foreach ($faqs as $faq): ?>
            <tr data-id="<?= $faq['id'] ?>">
              <td class="drag-handle">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                  <path
                    d="M360-160q-33 0-56.5-23.5T280-240q0-33 23.5-56.5T360-320q33 0 56.5 23.5T440-240q0 33-23.5 56.5T360-160Zm0-240q-33 0-56.5-23.5T280-480q0-33 23.5-56.5T360-560q33 0 56.5 23.5T440-480q0 33-23.5 56.5T360-400Zm0-240q-33 0-56.5-23.5T280-720q0-33 23.5-56.5T360-800q33 0 56.5 23.5T440-720q0 33-23.5 56.5T360-640Zm240 0q-33 0-56.5-23.5T520-720q0-33 23.5-56.5T600-800q33 0 56.5 23.5T680-720q0 33-23.5 56.5T600-640Zm0 240q-33 0-56.5-23.5T520-480q0-33 23.5-56.5T600-560q33 0 56.5 23.5T680-480q0 33-23.5 56.5T600-400Zm0 240q-33 0-56.5-23.5T520-240q0-33 23.5-56.5T600-320q33 0 56.5 23.5T680-240q0 33-23.5 56.5T600-160Z" />
                </svg>
              </td>
              <td><?= htmlspecialchars($faq['question']) ?></td>
              <td class="action-cell">
                <button class="button btn-secondary btn-sm edit-entry" data-id="<?= $faq['id'] ?>"
                  data-question="<?= htmlspecialchars($faq['question']) ?>"
                  data-answer="<?= htmlspecialchars($faq['answer']) ?>" data-order="<?= $faq['display_order'] ?>">
                  <span>EDIT</span>
                  <svg xmlns="http://www.w3.org/2000/svg" height="12px" viewBox="0 -960 960 960" width="24px"
                    fill="#ffffe3">
                    <path
                      d="M160-120q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm544-528 56-56-56-56-56 56 56 56Z" />
                  </svg>
                </button>

                <form method="POST" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $faq['id'] ?>">
                  <button type="submit" name="delete_faq" class="button btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this FAQ?')">
                    <span>DELETE</span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="12px" viewBox="0 -960 960 960" width="24px"
                      fill="#ffffe3">
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
        No FAQ entries found. Add your first entry using the button above.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add FAQ Modal -->
<div class="modal" id="add-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Add New FAQ</h2>
      <button class="close-modal">&times;</button>
    </div>

    <form method="POST" id="add-form">
      <input type="hidden" name="add_faq" value="1">

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

<!-- Edit FAQ Modal -->
<div class="modal" id="edit-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit FAQ</h2>
      <button class="close-modal">&times;</button>
    </div>

    <form method="POST" id="edit-form">
      <input type="hidden" name="id" id="edit-id">
      <input type="hidden" name="update_faq" value="1">

      <div class="form-group">
        <label>Question</label>
        <input type="text" name="question" id="edit-question" required>
      </div>

      <div class="form-group">
        <label>Answer</label>
        <textarea name="answer" id="edit-answer" rows="4" required></textarea>
      </div>

      <div class="form-group">
        <label>Display Order</label>
        <input type="number" name="display_order" id="edit-order" required>
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
  // Drag and drop functionality
  document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('faq-table-body');
    if (!table) return;

    let draggedRow = null;

    // Make rows draggable
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => {
      row.setAttribute('draggable', true);

      row.addEventListener('dragstart', function (e) {
        draggedRow = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', this.dataset.id);
      });

      row.addEventListener('dragend', function () {
        this.classList.remove('dragging');
        this.classList.remove('drop-over-above', 'drop-over-below');
      });
    });

    // Handle drag over
    table.addEventListener('dragover', function (e) {
      e.preventDefault();
      const afterElement = getDragAfterElement(table, e.clientY);
      const currentRow = e.target.closest('tr');

      // Remove any existing drop indicators
      rows.forEach(row => {
        row.classList.remove('drop-over-above', 'drop-over-below');
      });

      if (afterElement) {
        const rect = afterElement.getBoundingClientRect();
        const offset = e.clientY - rect.top;

        if (offset < rect.height / 2) {
          afterElement.classList.add('drop-over-above');
        } else {
          afterElement.classList.add('drop-over-below');
        }
      }
    });

    // Handle drop
    table.addEventListener('drop', function (e) {
      e.preventDefault();
      const afterElement = getDragAfterElement(table, e.clientY);

      if (draggedRow) {
        if (afterElement) {
          const rect = afterElement.getBoundingClientRect();
          const offset = e.clientY - rect.top;

          if (offset < rect.height / 2) {
            table.insertBefore(draggedRow, afterElement);
          } else {
            table.insertBefore(draggedRow, afterElement.nextSibling);
          }
        } else {
          table.appendChild(draggedRow);
        }

        // Update display order numbers
        updateDisplayOrders();

        // Remove drop indicators
        rows.forEach(row => {
          row.classList.remove('drop-over-above', 'drop-over-below');
        });

        // Save new order to database
        saveNewOrder();
      }
    });

    // Calculate position for drag
    function getDragAfterElement(container, y) {
      const draggableElements = [...container.querySelectorAll('tr:not(.dragging)')];

      return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;

        if (offset < 0 && offset > closest.offset) {
          return { offset: offset, element: child };
        } else {
          return closest;
        }
      }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // Update display order numbers in the UI
    function updateDisplayOrders() {
      const rows = table.querySelectorAll('tr');
      rows.forEach((row, index) => {
        const orderCell = row.querySelector('.order-cell');
        if (orderCell) {
          orderCell.textContent = index + 1;
        }
      });
    }

    // Send new order to server
    function saveNewOrder() {
      const newOrder = [];
      const rows = table.querySelectorAll('tr');

      rows.forEach((row, index) => {
        newOrder.push({
          id: row.dataset.id,
          display_order: index + 1
        });
      });

      // Send AJAX request to save new order
      fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          'reorder_faqs': '1',
          'new_order': JSON.stringify(newOrder)
        })
      })
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            console.error('Error saving order:', data.error);
            alert('Failed to save new order: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while saving the order.');
        });
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
    document.getElementById('add-form').reset();
    openModal('add-modal');
  });

  // Edit modal functionality
  const editButtons = document.querySelectorAll('.edit-entry');
  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      document.getElementById('edit-id').value = button.dataset.id;
      document.getElementById('edit-question').value = button.dataset.question;
      document.getElementById('edit-answer').value = button.dataset.answer;
      document.getElementById('edit-order').value = button.dataset.order;
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
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/adminlayout.php';
?>