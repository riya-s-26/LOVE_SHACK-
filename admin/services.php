<?php
/**
 * THE LOVE SHACK — Admin: Additional Services Management
 */

declare(strict_types=1);

$adminPage = 'services';
$adminTitle = 'Additional Services';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$notice = '';
$error = '';

// Add / Edit Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$pdo) {
        $error = "Database offline. Please configure MySQL to save changes.";
    } else {
        if ($_POST['action'] === 'add_service') {
            $cat = trim((string)($_POST['category'] ?? ''));
            $name = trim((string)($_POST['name'] ?? ''));
            $desc = trim((string)($_POST['description'] ?? ''));
            $price = (float)($_POST['price'] ?? 0);
            $pricingType = trim((string)($_POST['pricing_type'] ?? 'per_stay'));

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO additional_services (category, name, description, price, pricing_type, is_active, display_order)
                    VALUES (?, ?, ?, ?, ?, 1, 99)
                ");
                $stmt->execute([$cat, $name, $desc, $price, $pricingType]);
                $notice = "Service '{$name}' created successfully!";
            } catch (Exception $e) {
                $error = "Error adding service: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'toggle_active') {
            $id = (int)($_POST['service_id'] ?? 0);
            $curr = (int)($_POST['current_status'] ?? 1);
            $newStatus = $curr === 1 ? 0 : 1;
            try {
                $stmt = $pdo->prepare("UPDATE additional_services SET is_active = ? WHERE id = ?");
                $stmt->execute([$newStatus, $id]);
                $notice = "Service status toggled successfully.";
            } catch (Exception $e) {
                $error = "Error updating service status: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'delete_service') {
            $id = (int)($_POST['service_id'] ?? 0);
            try {
                $stmt = $pdo->prepare("DELETE FROM additional_services WHERE id = ?");
                $stmt->execute([$id]);
                $notice = "Service deleted successfully.";
            } catch (Exception $e) {
                $error = "Error deleting service: " . $e->getMessage();
            }
        }
    }
}

// Fetch all services
$services = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM additional_services ORDER BY category ASC, display_order ASC");
        $services = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching services: " . $e->getMessage());
    }
} else {
    $services = get_additional_services();
}
?>

<div class="admin-header-row">
  <div>
    <h1>Additional Services & Stay Extras</h1>
    <p>Manage add-ons presented to visitors during Step 5 of the booking workflow (only shown after availability is confirmed).</p>
  </div>
</div>

<?php if (!empty($notice)): ?>
  <div style="background: #ecfdf5; border-left: 4px solid var(--color-secondary); color: #065f46; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px;">
    <?= htmlspecialchars($notice) ?>
  </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<!-- Add Service Card -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Add New Service Extra</h2>
  </div>

  <form method="POST" action="services.php">
    <input type="hidden" name="action" value="add_service">

    <div class="admin-grid-3">
      <div class="admin-form-group">
        <label for="category">Category *</label>
        <select id="category" name="category" required>
          <option value="Dining Extras">Dining Extras</option>
          <option value="Romantic Add-ons">Romantic Add-ons</option>
          <option value="Celebration Packages">Celebration Packages</option>
          <option value="Other Extras">Other Extras</option>
        </select>
      </div>

      <div class="admin-form-group">
        <label for="name">Service Name *</label>
        <input type="text" id="name" name="name" placeholder="e.g. Sunset Snorkel Tour" required>
      </div>

      <div class="admin-form-group">
        <label for="price">Price (USD) *</label>
        <input type="number" id="price" name="price" step="0.01" min="0" placeholder="120.00" required>
      </div>
    </div>

    <div class="admin-form-group">
      <label for="description">Service Description *</label>
      <textarea id="description" name="description" rows="2" placeholder="Describe the inclusions and experience..." required></textarea>
    </div>

    <div style="display: flex; justify-content: flex-end;">
      <button type="submit" class="btn btn-teal" style="padding: 10px 24px; font-size: 0.78rem;">
        Add Service Extra
      </button>
    </div>
  </form>
</div>

<!-- All Services Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Configured Services (<?= count($services) ?>)</h2>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Category</th>
          <th>Service Name</th>
          <th>Description</th>
          <th>Price</th>
          <th>Pricing Type</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($services as $s): ?>
          <tr>
            <td><strong><?= htmlspecialchars($s['category']) ?></strong></td>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td style="max-width: 320px; font-size: 0.82rem; color: var(--color-text-muted);">
              <?= htmlspecialchars($s['description']) ?>
            </td>
            <td><strong>$<?= number_format((float)$s['price'], 2) ?></strong></td>
            <td><?= htmlspecialchars($s['pricing_type'] ?? 'per_stay') ?></td>
            <td>
              <?php if (!empty($s['is_active'])): ?>
                <span class="badge badge-confirmed">ACTIVE</span>
              <?php else: ?>
                <span class="badge badge-declined">INACTIVE</span>
              <?php endif; ?>
            </td>
            <td>
              <div style="display: flex; gap: 8px;">
                <form method="POST" action="services.php" style="display: inline;">
                  <input type="hidden" name="action" value="toggle_active">
                  <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
                  <input type="hidden" name="current_status" value="<?= $s['is_active'] ?? 1 ?>">
                  <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.7rem;">
                    <?= !empty($s['is_active']) ? 'Deactivate' : 'Activate' ?>
                  </button>
                </form>

                <form method="POST" action="services.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this service?');">
                  <input type="hidden" name="action" value="delete_service">
                  <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
                  <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.7rem; border-color: #fecaca; color: #b91c1c;">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
