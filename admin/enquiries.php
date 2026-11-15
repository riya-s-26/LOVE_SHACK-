<?php
/**
 * THE LOVE SHACK — Admin: Contact Enquiries Management
 */

declare(strict_types=1);

$adminPage = 'enquiries';
$adminTitle = 'Contact Enquiries';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$notice = '';

// Update status action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $enquiryId = (int)($_POST['enquiry_id'] ?? 0);
    $newStatus = trim((string)($_POST['status'] ?? ''));
    $allowed = ['NEW', 'READ', 'RESPONDED', 'ARCHIVED'];

    if ($pdo && in_array($newStatus, $allowed, true)) {
        try {
            $stmt = $pdo->prepare("UPDATE enquiries SET status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $enquiryId]);
            $notice = "Enquiry #{$enquiryId} status updated to {$newStatus}.";
        } catch (Exception $e) {
            $notice = "Failed to update enquiry: " . $e->getMessage();
        }
    }
}

// Single enquiry detail
$selectedEnquiry = null;
$detailId = (int)($_GET['id'] ?? 0);
if ($detailId > 0 && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM enquiries WHERE id = ?");
        $stmt->execute([$detailId]);
        $selectedEnquiry = $stmt->fetch();
        // If status was NEW, auto-mark as READ
        if ($selectedEnquiry && $selectedEnquiry['status'] === 'NEW') {
            $pdo->prepare("UPDATE enquiries SET status = 'READ' WHERE id = ?")->execute([$detailId]);
            $selectedEnquiry['status'] = 'READ';
        }
    } catch (Exception $e) {
        error_log("Error fetching enquiry detail: " . $e->getMessage());
    }
}

// Fetch all enquiries
$enquiries = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM enquiries ORDER BY id DESC");
        $enquiries = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching enquiries: " . $e->getMessage());
    }
}
?>

<div class="admin-header-row">
  <div>
    <h1>Guest Inquiries & Messages</h1>
    <p>Messages submitted through the direct contact form on the website.</p>
  </div>
</div>

<?php if (!empty($notice)): ?>
  <div style="background: #ecfdf5; border-left: 4px solid var(--color-secondary); color: #065f46; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px;">
    <?= htmlspecialchars($notice) ?>
  </div>
<?php endif; ?>

<!-- Selected Enquiry Message Viewer -->
<?php if ($selectedEnquiry): ?>
  <div class="admin-card" style="border: 2px solid var(--color-secondary);">
    <div class="admin-card-header">
      <h2>Inquiry from <?= htmlspecialchars($selectedEnquiry['name']) ?></h2>
      <a href="enquiries.php" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.72rem;">Close ×</a>
    </div>

    <div class="admin-grid-2" style="margin-bottom: 20px;">
      <div>
        <p style="margin: 4px 0;"><strong>Sender Name:</strong> <?= htmlspecialchars($selectedEnquiry['name']) ?></p>
        <p style="margin: 4px 0;"><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($selectedEnquiry['email']) ?>" style="color: var(--color-secondary);"><?= htmlspecialchars($selectedEnquiry['email']) ?></a></p>
        <p style="margin: 4px 0;"><strong>Phone/WhatsApp:</strong> <?= htmlspecialchars($selectedEnquiry['phone'] ?: 'None provided') ?></p>
      </div>
      <div>
        <p style="margin: 4px 0;"><strong>Subject:</strong> <?= htmlspecialchars($selectedEnquiry['subject']) ?></p>
        <p style="margin: 4px 0;"><strong>Received:</strong> <?= date('F j, Y, g:i a', strtotime($selectedEnquiry['created_at'])) ?></p>
        <p style="margin: 4px 0;"><strong>Status:</strong> <span class="badge badge-<?= strtolower($selectedEnquiry['status']) ?>"><?= htmlspecialchars($selectedEnquiry['status']) ?></span></p>
      </div>
    </div>

    <div style="background: var(--color-bg-aqua); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 20px; margin-bottom: 24px;">
      <span class="admin-stat-label" style="display:block; margin-bottom: 8px;">Message Content</span>
      <p style="margin: 0; font-size: 1rem; line-height: 1.7; color: var(--color-text-body);">
        <?= nl2br(htmlspecialchars($selectedEnquiry['message'])) ?>
      </p>
    </div>

    <!-- Status Updater -->
    <form method="POST" action="enquiries.php" style="display: flex; align-items: center; gap: 14px; background: #fafafa; padding: 14px; border-radius: var(--radius-sm);">
      <input type="hidden" name="action" value="update_status">
      <input type="hidden" name="enquiry_id" value="<?= $selectedEnquiry['id'] ?>">
      <label style="font-weight: 700; font-size: 0.85rem; color: var(--color-primary);">Update Status:</label>
      <select name="status" style="padding: 8px 14px; border: 1px solid var(--color-border); border-radius: 6px; font-weight: 600;">
        <option value="NEW" <?= $selectedEnquiry['status'] === 'NEW' ? 'selected' : '' ?>>NEW</option>
        <option value="READ" <?= $selectedEnquiry['status'] === 'READ' ? 'selected' : '' ?>>READ</option>
        <option value="RESPONDED" <?= $selectedEnquiry['status'] === 'RESPONDED' ? 'selected' : '' ?>>RESPONDED</option>
        <option value="ARCHIVED" <?= $selectedEnquiry['status'] === 'ARCHIVED' ? 'selected' : '' ?>>ARCHIVED</option>
      </select>
      <button type="submit" class="btn btn-teal" style="padding: 8px 18px; font-size: 0.75rem;">Save Status</button>
      <a href="mailto:<?= htmlspecialchars($selectedEnquiry['email']) ?>?subject=Re: <?= urlencode($selectedEnquiry['subject']) ?>" class="btn btn-outline" style="padding: 8px 18px; font-size: 0.75rem;">Reply via Email ↗</a>
    </form>
  </div>
<?php endif; ?>

<!-- All Enquiries Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>All Inquiries (<?= count($enquiries) ?>)</h2>
  </div>

  <?php if (empty($enquiries)): ?>
    <p style="color: var(--color-text-muted); padding: 12px 0;">No inquiries in the database yet.</p>
  <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($enquiries as $enq): ?>
            <tr>
              <td><?= date('M j, Y', strtotime($enq['created_at'])) ?></td>
              <td><strong><?= htmlspecialchars($enq['name']) ?></strong></td>
              <td><?= htmlspecialchars($enq['email']) ?></td>
              <td><?= htmlspecialchars($enq['phone'] ?: '—') ?></td>
              <td><?= htmlspecialchars($enq['subject']) ?></td>
              <td>
                <span class="badge badge-<?= strtolower($enq['status']) ?>"><?= htmlspecialchars($enq['status']) ?></span>
              </td>
              <td>
                <a href="enquiries.php?id=<?= $enq['id'] ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.72rem;">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
