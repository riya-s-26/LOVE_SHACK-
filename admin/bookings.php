<?php
/**
 * THE LOVE SHACK — Admin: Manage Booking Requests
 */

declare(strict_types=1);

$adminPage = 'bookings';
$adminTitle = 'Booking Requests';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$notice = '';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $bookingId = (int)($_POST['booking_id'] ?? 0);
    $newStatus = trim((string)($_POST['status'] ?? ''));
    $allowedStatuses = ['PENDING', 'CONFIRMED', 'DECLINED', 'CANCELLED'];

    if ($pdo && in_array($newStatus, $allowedStatuses, true)) {
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$newStatus, $bookingId]);
            $notice = "Booking #{$bookingId} status successfully updated to {$newStatus}.";
        } catch (Exception $e) {
            $notice = "Failed to update status: " . $e->getMessage();
        }
    }
}

// Fetch single booking detail if requested
$selectedBooking = null;
$selectedServices = [];
$detailId = (int)($_GET['id'] ?? 0);

if ($detailId > 0 && $pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT b.*, bg.first_name, bg.last_name, bg.email, bg.phone, bg.country, bg.special_requests
            FROM bookings b
            LEFT JOIN booking_guests bg ON b.id = bg.booking_id
            WHERE b.id = ?
        ");
        $stmt->execute([$detailId]);
        $selectedBooking = $stmt->fetch();

        if ($selectedBooking) {
            $stmt2 = $pdo->prepare("SELECT * FROM booking_services WHERE booking_id = ?");
            $stmt2->execute([$detailId]);
            $selectedServices = $stmt2->fetchAll();
        }
    } catch (Exception $e) {
        error_log("Error fetching booking detail: " . $e->getMessage());
    }
}

// Fetch all bookings
$bookings = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT b.*, bg.first_name, bg.last_name, bg.email, bg.phone
            FROM bookings b
            LEFT JOIN booking_guests bg ON b.id = bg.booking_id
            ORDER BY b.id DESC
        ");
        $bookings = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching all bookings: " . $e->getMessage());
    }
}
?>

<div class="admin-header-row">
  <div>
    <h1>Booking Requests & Reservations</h1>
    <p>Review incoming requests, verify requested dates, inspect extras, and manage reservation statuses.</p>
  </div>
</div>

<?php if (!empty($notice)): ?>
  <div style="background: #ecfdf5; border-left: 4px solid var(--color-secondary); color: #065f46; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px;">
    <?= htmlspecialchars($notice) ?>
  </div>
<?php endif; ?>

<!-- Detail Modal / Drawer if a booking is selected -->
<?php if ($selectedBooking): ?>
  <div class="admin-card" style="border: 2px solid var(--color-secondary);">
    <div class="admin-card-header">
      <h2>Booking Details: <?= htmlspecialchars($selectedBooking['booking_reference']) ?></h2>
      <a href="bookings.php" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.72rem;">Close Details ×</a>
    </div>

    <div class="admin-grid-3" style="margin-bottom: 24px;">
      <div>
        <span class="admin-stat-label">Guest Contact</span>
        <strong style="display:block; font-size: 1.1rem; color: var(--color-primary);">
          <?= htmlspecialchars($selectedBooking['first_name'] . ' ' . $selectedBooking['last_name']) ?>
        </strong>
        <p style="margin: 4px 0;">Email: <a href="mailto:<?= htmlspecialchars($selectedBooking['email']) ?>" style="color: var(--color-secondary);"><?= htmlspecialchars($selectedBooking['email']) ?></a></p>
        <p style="margin: 0;">Phone/WhatsApp: <?= htmlspecialchars($selectedBooking['phone'] ?: 'None provided') ?></p>
        <?php if (!empty($selectedBooking['country'])): ?>
          <p style="margin: 4px 0 0;">Country: <?= htmlspecialchars($selectedBooking['country']) ?></p>
        <?php endif; ?>
      </div>

      <div>
        <span class="admin-stat-label">Dates & Party</span>
        <p style="margin: 4px 0;"><strong>Check-in:</strong> <?= htmlspecialchars($selectedBooking['checkin_date']) ?></p>
        <p style="margin: 4px 0;"><strong>Check-out:</strong> <?= htmlspecialchars($selectedBooking['checkout_date']) ?></p>
        <p style="margin: 4px 0;"><strong>Duration:</strong> <?= (int)$selectedBooking['nights'] ?> night(s)</p>
        <p style="margin: 4px 0;"><strong>Party:</strong> <?= (int)$selectedBooking['adults'] ?> Adults, <?= (int)$selectedBooking['children'] ?> Children (Total: <?= (int)$selectedBooking['total_guests'] ?>)</p>
      </div>

      <div>
        <span class="admin-stat-label">Price Summary</span>
        <p style="margin: 4px 0;">Accommodation: $<?= number_format((float)$selectedBooking['accommodation_total'], 2) ?></p>
        <p style="margin: 4px 0;">Cleaning Fee: $<?= number_format((float)$selectedBooking['cleaning_fee'], 2) ?></p>
        <p style="margin: 4px 0;">Additional Extras: $<?= number_format((float)$selectedBooking['services_total'], 2) ?></p>
        <p style="margin: 6px 0 0; font-size: 1.15rem; font-weight: 700; color: var(--color-primary);">
          Total: $<?= number_format((float)$selectedBooking['estimated_total'], 2) ?> USD
        </p>
      </div>
    </div>

    <?php if (!empty($selectedServices)): ?>
      <div style="background: var(--color-bg-aqua); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 18px; margin-bottom: 24px;">
        <span class="admin-stat-label" style="display:block; margin-bottom: 8px;">Selected Additional Services</span>
        <ul style="list-style: none; padding: 0; margin: 0;">
          <?php foreach ($selectedServices as $svc): ?>
            <li style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed var(--color-border);">
              <span><?= htmlspecialchars($svc['service_name']) ?> (Qty: <?= (int)$svc['quantity'] ?>)</span>
              <strong>$<?= number_format((float)$svc['total_price'], 2) ?></strong>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (!empty($selectedBooking['special_requests'])): ?>
      <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: var(--radius-sm); padding: 16px; margin-bottom: 24px;">
        <span class="admin-stat-label" style="color: #92400e;">Guest Notes / Special Requests</span>
        <p style="margin: 6px 0 0; color: #78350f;"><?= nl2br(htmlspecialchars($selectedBooking['special_requests'])) ?></p>
      </div>
    <?php endif; ?>

    <!-- Update Status Form -->
    <form method="POST" action="bookings.php" style="display: flex; align-items: center; gap: 14px; background: #fafafa; padding: 16px; border-radius: var(--radius-sm);">
      <input type="hidden" name="action" value="update_status">
      <input type="hidden" name="booking_id" value="<?= $selectedBooking['id'] ?>">
      <label style="font-weight: 700; font-size: 0.85rem; color: var(--color-primary);">Change Reservation Status:</label>
      <select name="status" style="padding: 8px 14px; border: 1px solid var(--color-border); border-radius: 6px; font-weight: 600;">
        <option value="PENDING" <?= $selectedBooking['status'] === 'PENDING' ? 'selected' : '' ?>>PENDING</option>
        <option value="CONFIRMED" <?= $selectedBooking['status'] === 'CONFIRMED' ? 'selected' : '' ?>>CONFIRMED</option>
        <option value="DECLINED" <?= $selectedBooking['status'] === 'DECLINED' ? 'selected' : '' ?>>DECLINED</option>
        <option value="CANCELLED" <?= $selectedBooking['status'] === 'CANCELLED' ? 'selected' : '' ?>>CANCELLED</option>
      </select>
      <button type="submit" class="btn btn-teal" style="padding: 8px 18px; font-size: 0.75rem;">Update Status</button>
    </form>
  </div>
<?php endif; ?>

<!-- All Bookings Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>All Booking Requests (<?= count($bookings) ?>)</h2>
  </div>

  <?php if (empty($bookings)): ?>
    <p style="color: var(--color-text-muted); padding: 12px 0;">No reservations in the database yet.</p>
  <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Reference</th>
            <th>Received Date</th>
            <th>Guest Name</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Nights</th>
            <th>Guests</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bookings as $b): ?>
            <tr>
              <td><strong><?= htmlspecialchars($b['booking_reference']) ?></strong></td>
              <td><?= date('M j, Y', strtotime($b['created_at'])) ?></td>
              <td>
                <?= htmlspecialchars(trim(($b['first_name'] ?? '') . ' ' . ($b['last_name'] ?? '')) ?: 'Guest') ?><br>
                <small style="color: var(--color-text-muted);"><?= htmlspecialchars($b['email'] ?? '') ?></small>
              </td>
              <td><?= htmlspecialchars($b['checkin_date']) ?></td>
              <td><?= htmlspecialchars($b['checkout_date']) ?></td>
              <td><?= (int)$b['nights'] ?></td>
              <td><?= (int)$b['total_guests'] ?></td>
              <td><strong>$<?= number_format((float)$b['estimated_total'], 2) ?></strong></td>
              <td>
                <span class="badge badge-<?= strtolower($b['status']) ?>"><?= htmlspecialchars($b['status']) ?></span>
              </td>
              <td>
                <a href="bookings.php?id=<?= $b['id'] ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.72rem;">Review & Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
