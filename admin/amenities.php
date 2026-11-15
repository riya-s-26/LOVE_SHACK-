<?php
/**
 * THE LOVE SHACK — Admin: Amenities Management
 */

declare(strict_types=1);

$adminPage = 'amenities';
$adminTitle = 'Amenities Management';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$notice = '';
$error = '';

$categories = [
    'Popular',
    'Bathroom',
    'Bedroom and laundry',
    'Entertainment',
    'Kitchen and dining',
    'Outdoor',
    'Parking and additional facilities'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$pdo) {
        $error = "Database connection unavailable.";
    } else {
        if ($_POST['action'] === 'add_amenity') {
            $cat = trim((string)($_POST['category'] ?? 'Popular'));
            $name = trim((string)($_POST['name'] ?? ''));
            $desc = trim((string)($_POST['description'] ?? ''));
            $isPopular = isset($_POST['is_popular']) ? 1 : 0;

            if (empty($name)) {
                $error = "Amenity name is required.";
            } else {
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO amenities (property_id, category, name, description, is_popular, display_order, is_active)
                        VALUES (1, ?, ?, ?, ?, 99, 1)
                    ");
                    $stmt->execute([$cat, $name, $desc, $isPopular]);
                    $notice = "Amenity '{$name}' successfully added!";
                } catch (Exception $e) {
                    $error = "Error adding amenity: " . $e->getMessage();
                }
            }
        } elseif ($_POST['action'] === 'delete_amenity') {
            $id = (int)($_POST['amenity_id'] ?? 0);
            try {
                $stmt = $pdo->prepare("DELETE FROM amenities WHERE id = ?");
                $stmt->execute([$id]);
                $notice = "Amenity removed.";
            } catch (Exception $e) {
                $error = "Failed to remove amenity: " . $e->getMessage();
            }
        }
    }
}

// Fetch all amenities
$amenitiesList = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM amenities WHERE property_id = 1 ORDER BY category ASC, display_order ASC");
        $amenitiesList = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching amenities: " . $e->getMessage());
    }
}
?>

<div class="admin-header-row">
  <div>
    <h1>Property Amenities Management</h1>
    <p>Configure verified property facilities categorized into the 7 Airbnb-inspired groups.</p>
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

<!-- Add Amenity Form -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Add Property Amenity</h2>
  </div>

  <form method="POST" action="amenities.php">
    <input type="hidden" name="action" value="add_amenity">

    <div class="admin-grid-3">
      <div class="admin-form-group">
        <label for="category">Category *</label>
        <select id="category" name="category" required>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="admin-form-group">
        <label for="name">Amenity Name *</label>
        <input type="text" id="name" name="name" placeholder="e.g. Nespresso Coffee Bar" required>
      </div>

      <div class="admin-form-group">
        <label for="description">Short Description / Specification</label>
        <input type="text" id="description" name="description" placeholder="e.g. Complimentary organic starter pods provided">
      </div>
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between;">
      <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
        <input type="checkbox" name="is_popular" value="1" style="width: auto;">
        Highlight in Homepage Popular Grid (Top 10)
      </label>

      <button type="submit" class="btn btn-teal" style="padding: 10px 24px; font-size: 0.78rem;">
        Add Amenity
      </button>
    </div>
  </form>
</div>

<!-- All Amenities Grouped -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Configured Amenities (<?= count($amenitiesList) ?>)</h2>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Category</th>
          <th>Amenity Name</th>
          <th>Description</th>
          <th>Highlight</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($amenitiesList as $a): ?>
          <tr>
            <td><strong><?= htmlspecialchars($a['category']) ?></strong></td>
            <td><?= htmlspecialchars($a['name']) ?></td>
            <td style="color: var(--color-text-muted); font-size: 0.85rem;">
              <?= htmlspecialchars($a['description'] ?: '—') ?>
            </td>
            <td>
              <?php if (!empty($a['is_popular'])): ?>
                <span class="badge badge-confirmed">POPULAR CARD</span>
              <?php else: ?>
                <span class="badge badge-read">STANDARD</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" action="amenities.php" onsubmit="return confirm('Delete this amenity?');" style="display:inline;">
                <input type="hidden" name="action" value="delete_amenity">
                <input type="hidden" name="amenity_id" value="<?= $a['id'] ?>">
                <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.7rem; border-color: #fecaca; color: #b91c1c;">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
