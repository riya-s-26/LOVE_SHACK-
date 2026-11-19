<?php
/**
 * THE LOVE SHACK — Admin: Content Management
 */

declare(strict_types=1);

$adminPage = 'content';
$adminTitle = 'Content Management';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$notice = '';
$error = '';

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_content') {
    if ($pdo) {
        try {
            $pdo->beginTransaction();

            // 1. Update site_settings
            $settingsFields = [
                'site_title', 'site_tagline', 'concierge_email', 'concierge_phone',
                'property_location', 'checkin_time', 'checkout_time', 'cancellation_policy'
            ];
            $stmtSet = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
            foreach ($settingsFields as $key) {
                if (isset($_POST[$key])) {
                    $stmtSet->execute([trim((string)$_POST[$key]), $key]);
                }
            }

            // 2. Update property info
            $stmtProp = $pdo->prepare("
                UPDATE properties SET 
                    hero_heading = ?, 
                    hero_description = ?, 
                    intro_title = ?, 
                    intro_content = ?,
                    bedrooms = ?,
                    bathrooms = ?,
                    max_guests = ?
                WHERE id = 1
            ");
            $stmtProp->execute([
                trim((string)($_POST['hero_heading'] ?? '')),
                trim((string)($_POST['hero_description'] ?? '')),
                trim((string)($_POST['intro_title'] ?? '')),
                trim((string)($_POST['intro_content'] ?? '')),
                (int)($_POST['bedrooms'] ?? 3),
                (int)($_POST['bathrooms'] ?? 3),
                (int)($_POST['max_guests'] ?? 6)
            ]);

            $pdo->commit();
            $notice = "Website content successfully updated in the database!";
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = "Failed to update content: " . $e->getMessage();
        }
    } else {
        $notice = "Database offline. Updates will take effect when MySQL is active.";
    }
}

// Fetch current values
$settings = get_site_settings();
$property = get_property_details();
?>

<div class="admin-header-row">
  <div>
    <h1>Website Content Management</h1>
    <p>Update homepage copy, property introduction, guest capacity, and concierge contact details.</p>
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

<form method="POST" action="content.php">
  <input type="hidden" name="action" value="save_content">

  <!-- General Brand Information -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Brand & Identity</h2>
    </div>

    <div class="admin-grid-2">
      <div class="admin-form-group">
        <label for="site_title">Website Title</label>
        <input type="text" id="site_title" name="site_title" value="<?= htmlspecialchars($settings['site_title'] ?? 'The Love Shack Aruba') ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="site_tagline">Brand Subtitle / Tagline</label>
        <input type="text" id="site_tagline" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>" required>
      </div>
    </div>
  </div>

  <!-- Hero Section -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Homepage Hero Section</h2>
    </div>

    <div class="admin-form-group">
      <label for="hero_heading">Hero Heading</label>
      <input type="text" id="hero_heading" name="hero_heading" value="<?= htmlspecialchars($property['hero_heading'] ?? '') ?>" required>
    </div>

    <div class="admin-form-group">
      <label for="hero_description">Hero Description</label>
      <textarea id="hero_description" name="hero_description" rows="3" required><?= htmlspecialchars($property['hero_description'] ?? '') ?></textarea>
    </div>
  </div>

  <!-- Editorial Introduction & Property Specs -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Property Narrative & Specifications</h2>
    </div>

    <div class="admin-grid-3" style="margin-bottom: 16px;">
      <div class="admin-form-group">
        <label for="bedrooms">Bedrooms</label>
        <input type="number" id="bedrooms" name="bedrooms" min="1" max="10" value="<?= (int)($property['bedrooms'] ?? 3) ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="bathrooms">Bathrooms</label>
        <input type="number" id="bathrooms" name="bathrooms" min="1" max="10" value="<?= (int)($property['bathrooms'] ?? 3) ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="max_guests">Max Guests</label>
        <input type="number" id="max_guests" name="max_guests" min="1" max="20" value="<?= (int)($property['max_guests'] ?? 6) ?>" required>
      </div>
    </div>

    <div class="admin-form-group">
      <label for="intro_title">Introduction Title (Savaneta · Aruba)</label>
      <input type="text" id="intro_title" name="intro_title" value="<?= htmlspecialchars($property['intro_title'] ?? '') ?>" required>
    </div>

    <div class="admin-form-group">
      <label for="intro_content">Introduction Story & Neighborhood Description</label>
      <textarea id="intro_content" name="intro_content" rows="6" required><?= htmlspecialchars($property['intro_content'] ?? '') ?></textarea>
    </div>
  </div>

  <!-- Concierge & Contact Policies -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Concierge Contact & Check-in Policies</h2>
    </div>

    <div class="admin-grid-3">
      <div class="admin-form-group">
        <label for="concierge_email">Concierge Email</label>
        <input type="email" id="concierge_email" name="concierge_email" value="<?= htmlspecialchars($settings['concierge_email'] ?? '') ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="concierge_phone">Phone / WhatsApp</label>
        <input type="text" id="concierge_phone" name="concierge_phone" value="<?= htmlspecialchars($settings['concierge_phone'] ?? '') ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="property_location">Location Summary</label>
        <input type="text" id="property_location" name="property_location" value="<?= htmlspecialchars($settings['property_location'] ?? '') ?>" required>
      </div>
    </div>

    <div class="admin-grid-2">
      <div class="admin-form-group">
        <label for="checkin_time">Check-in Time</label>
        <input type="text" id="checkin_time" name="checkin_time" value="<?= htmlspecialchars($settings['checkin_time'] ?? '15:00') ?>" required>
      </div>

      <div class="admin-form-group">
        <label for="checkout_time">Check-out Time</label>
        <input type="text" id="checkout_time" name="checkout_time" value="<?= htmlspecialchars($settings['checkout_time'] ?? '11:00') ?>" required>
      </div>
    </div>

    <div class="admin-form-group">
      <label for="cancellation_policy">Cancellation Policy</label>
      <textarea id="cancellation_policy" name="cancellation_policy" rows="3"><?= htmlspecialchars($settings['cancellation_policy'] ?? '') ?></textarea>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 40px;">
    <button type="submit" class="btn btn-teal" style="padding: 16px 36px; font-size: 0.85rem;">
      Save All Content Changes
    </button>
  </div>
</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
