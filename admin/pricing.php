<?php
/**
 * THE LOVE SHACK — Admin: Pricing & Seasonal Rates Management
 */

declare(strict_types=1);

$adminPage = 'pricing';
$adminTitle = 'Pricing & Seasonal Rates';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$notice = '';
$error = '';

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$pdo) {
        $error = "Database offline. Connect MySQL to save changes.";
    } else {
        if ($_POST['action'] === 'save_base_pricing') {
            $baseRate = (float)($_POST['default_nightly_rate'] ?? 650);
            $cleaning = (float)($_POST['cleaning_fee'] ?? 180);
            $minStay = (int)($_POST['min_stay_nights'] ?? 3);
            $maxGuests = (int)($_POST['max_guests'] ?? 6);

            try {
                $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([(string)$baseRate, 'default_nightly_rate']);
                $stmt->execute([(string)$cleaning, 'cleaning_fee']);
                $stmt->execute([(string)$minStay, 'min_stay_nights']);
                $stmt->execute([(string)$maxGuests, 'max_guests']);

                $stmt2 = $pdo->prepare("UPDATE properties SET base_nightly_rate = ?, max_guests = ? WHERE id = 1");
                $stmt2->execute([$baseRate, $maxGuests]);

                $notice = "Base pricing policies updated successfully!";
            } catch (Exception $e) {
                $error = "Failed to update pricing: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'add_seasonal_rule') {
            $title = trim((string)($_POST['title'] ?? ''));
            $start = trim((string)($_POST['start_date'] ?? ''));
            $end = trim((string)($_POST['end_date'] ?? ''));
            $rate = (float)($_POST['nightly_rate'] ?? 0);
            $min = (int)($_POST['min_stay_nights'] ?? 3);
            $note = trim((string)($_POST['note'] ?? ''));

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO pricing_rules (title, start_date, end_date, nightly_rate, min_stay_nights, note, is_active)
                    VALUES (?, ?, ?, ?, ?, ?, 1)
                ");
                $stmt->execute([$title, $start, $end, $rate, $min, $note]);
                $notice = "Seasonal pricing rule '{$title}' added!";
            } catch (Exception $e) {
                $error = "Error adding seasonal rule: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'delete_rule') {
            $id = (int)($_POST['rule_id'] ?? 0);
            try {
                $stmt = $pdo->prepare("DELETE FROM pricing_rules WHERE id = ?");
                $stmt->execute([$id]);
                $notice = "Pricing rule deleted.";
            } catch (Exception $e) {
                $error = "Error deleting rule: " . $e->getMessage();
            }
        }
    }
}

$settings = get_site_settings();

// Fetch seasonal rules
$rules = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM pricing_rules ORDER BY start_date ASC");
        $rules = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching pricing rules: " . $e->getMessage());
    }
}
?>

<div class="admin-header-row">
  <div>
    <h1>Pricing Configuration & Seasonal Rules</h1>
    <p>Configure standard base rates, mandatory cleaning charges, and seasonal peak calendars.</p>
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

<!-- Base Rates Card -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Standard Base Pricing & Stay Policies</h2>
  </div>

  <form method="POST" action="pricing.php">
    <input type="hidden" name="action" value="save_base_pricing">

    <div class="admin-grid-2">
      <div class="admin-form-group">
        <label for="default_nightly_rate">Standard Nightly Base Rate (USD)</label>
        <input type="number" id="default_nightly_rate" name="default_nightly_rate" step="0.01" value="<?= (float)($settings['default_nightly_rate'] ?? 650) ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="cleaning_fee">Turnover / Cleaning Fee (USD)</label>
        <input type="number" id="cleaning_fee" name="cleaning_fee" step="0.01" value="<?= (float)($settings['cleaning_fee'] ?? 180) ?>" required>
      </div>
    </div>

    <div class="admin-grid-2">
      <div class="admin-form-group">
        <label for="min_stay_nights">Minimum Stay (Nights)</label>
        <input type="number" id="min_stay_nights" name="min_stay_nights" min="1" max="30" value="<?= (int)($settings['min_stay_nights'] ?? 3) ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="max_guests">Maximum Guest Capacity</label>
        <input type="number" id="max_guests" name="max_guests" min="1" max="20" value="<?= (int)($settings['max_guests'] ?? 6) ?>" required>
      </div>
    </div>

    <div style="display: flex; justify-content: flex-end;">
      <button type="submit" class="btn btn-teal" style="padding: 10px 24px; font-size: 0.78rem;">
        Save Base Pricing
      </button>
    </div>
  </form>
</div>

<!-- Add Seasonal Pricing Rule -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Add Seasonal Rate Rule</h2>
  </div>

  <form method="POST" action="pricing.php">
    <input type="hidden" name="action" value="add_seasonal_rule">

    <div class="admin-grid-3">
      <div class="admin-form-group">
        <label for="title">Season Name / Description</label>
        <input type="text" id="title" name="title" placeholder="e.g. Festive Holiday Peak" required>
      </div>

      <div class="admin-form-group">
        <label for="start_date">Start Date</label>
        <input type="date" id="start_date" name="start_date" required>
      </div>

      <div class="admin-form-group">
        <label for="end_date">End Date</label>
        <input type="date" id="end_date" name="end_date" required>
      </div>
    </div>

    <div class="admin-grid-3">
      <div class="admin-form-group">
        <label for="nightly_rate">Nightly Rate (USD)</label>
        <input type="number" id="nightly_rate" name="nightly_rate" step="0.01" min="0" placeholder="950.00" required>
      </div>

      <div class="admin-form-group">
        <label for="rule_min_stay">Min Nights for Season</label>
        <input type="number" id="rule_min_stay" name="min_stay_nights" min="1" value="4" required>
      </div>

      <div class="admin-form-group">
        <label for="note">Host Notes</label>
        <input type="text" id="note" name="note" placeholder="e.g. Christmas / New Year week">
      </div>
    </div>

    <div style="display: flex; justify-content: flex-end;">
      <button type="submit" class="btn btn-teal" style="padding: 10px 24px; font-size: 0.78rem;">
        Create Seasonal Rate
      </button>
    </div>
  </form>
</div>

<!-- Configured Seasonal Rules Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Configured Seasonal Rules (<?= count($rules) ?>)</h2>
  </div>

  <?php if (empty($rules)): ?>
    <p style="color: var(--color-text-muted); padding: 12px 0;">No seasonal rules configured. Standard base rate applies to all dates.</p>
  <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Season Title</th>
            <th>Date Range</th>
            <th>Nightly Rate</th>
            <th>Min Nights</th>
            <th>Note</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rules as $r): ?>
            <tr>
              <td><strong><?= htmlspecialchars($r['title']) ?></strong></td>
              <td><?= htmlspecialchars($r['start_date']) ?> &rarr; <?= htmlspecialchars($r['end_date']) ?></td>
              <td><strong>$<?= number_format((float)$r['nightly_rate'], 2) ?> USD</strong></td>
              <td><?= (int)$r['min_stay_nights'] ?> nights</td>
              <td style="color: var(--color-text-muted);"><?= htmlspecialchars($r['note'] ?: '—') ?></td>
              <td>
                <form method="POST" action="pricing.php" onsubmit="return confirm('Delete this seasonal rate?');" style="display:inline;">
                  <input type="hidden" name="action" value="delete_rule">
                  <input type="hidden" name="rule_id" value="<?= $r['id'] ?>">
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
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
