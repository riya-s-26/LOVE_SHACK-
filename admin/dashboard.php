<?php
/**
 * THE LOVE SHACK — Admin Dashboard
 */

declare(strict_types=1);

$adminPage = 'dashboard';
$adminTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

$totalBookings = 0;
$pendingBookings = 0;
$confirmedBookings = 0;
$totalEnquiries = 0;
$recentBookings = [];
$recentEnquiries = [];

if ($pdo) {
    try {
        $totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
        $pendingBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'PENDING'")->fetchColumn();
        $confirmedBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'CONFIRMED'")->fetchColumn();
        $totalEnquiries = (int)$pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();

        // Recent bookings with guest name
        $stmt = $pdo->query("
            SELECT b.*, bg.first_name, bg.last_name, bg.email, bg.phone 
            FROM bookings b 
            LEFT JOIN booking_guests bg ON b.id = bg.booking_id 
            ORDER BY b.id DESC LIMIT 5
        ");
        $recentBookings = $stmt->fetchAll();

        // Recent enquiries
        $stmt = $pdo->query("SELECT * FROM enquiries ORDER BY id DESC LIMIT 5");
        $recentEnquiries = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Dashboard query error: " . $e->getMessage());
    }
}
?>

<div class="admin-header-row">
  <div>
    <h1>Welcome, <?= htmlspecialchars($adminUser) ?></h1>
    <p>Live summary of reservation requests, guest communications, and property operations.</p>
  </div>
  <div>
    <a href="bookings.php" class="btn btn-teal" style="padding: 10px 20px; font-size: 0.76rem;">Manage All Bookings ↗</a>
  </div>
</div>

<!-- Stat Cards -->
<div class="admin-stats-grid">
  <div class="admin-stat-card highlight">
    <div class="admin-stat-label">Total Booking Requests</div>
    <div class="admin-stat-value"><?= $totalBookings ?></div>
  </div>

  <div class="admin-stat-card alert">
    <div class="admin-stat-label">Pending Review</div>
    <div class="admin-stat-value"><?= $pendingBookings ?></div>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-label">Confirmed Reservations</div>
    <div class="admin-stat-value"><?= $confirmedBookings ?></div>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-label">Contact Enquiries</div>
    <div class="admin-stat-value"><?= $totalEnquiries ?></div>
  </div>
</div>

<!-- Recent Bookings Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Recent Booking Requests</h2>
    <a href="bookings.php" class="btn-text-link">View All Bookings</a>
  </div>

  <?php if (empty($recentBookings)): ?>
    <p style="color: var(--color-text-muted); padding: 12px 0;">No booking requests received yet. Direct visitor inquiries submitted on the website will appear here instantly.</p>
  <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Reference</th>
            <th>Guest Name</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Nights</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentBookings as $b): ?>
            <tr>
              <td><strong><?= htmlspecialchars($b['booking_reference']) ?></strong></td>
              <td>
                <?= htmlspecialchars(trim(($b['first_name'] ?? '') . ' ' . ($b['last_name'] ?? '')) ?: 'Guest') ?><br>
                <small style="color: var(--color-text-muted);"><?= htmlspecialchars($b['email'] ?? '') ?></small>
              </td>
              <td><?= htmlspecialchars($b['checkin_date']) ?></td>
              <td><?= htmlspecialchars($b['checkout_date']) ?></td>
              <td><?= (int)$b['nights'] ?></td>
              <td>$<?= number_format((float)$b['estimated_total'], 2) ?></td>
              <td>
                <span class="badge badge-<?= strtolower($b['status']) ?>"><?= htmlspecialchars($b['status']) ?></span>
              </td>
              <td>
                <a href="bookings.php?id=<?= $b['id'] ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.72rem;">Details</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- Recent Enquiries Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Recent Contact Inquiries</h2>
    <a href="enquiries.php" class="btn-text-link">View All Enquiries</a>
  </div>

  <?php if (empty($recentEnquiries)): ?>
    <p style="color: var(--color-text-muted); padding: 12px 0;">No contact form enquiries received yet.</p>
  <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentEnquiries as $enq): ?>
            <tr>
              <td><?= date('M j, Y', strtotime($enq['created_at'])) ?></td>
              <td><?= htmlspecialchars($enq['name']) ?></td>
              <td><?= htmlspecialchars($enq['email']) ?></td>
              <td><?= htmlspecialchars($enq['subject']) ?></td>
              <td>
                <span class="badge badge-<?= strtolower($enq['status']) ?>"><?= htmlspecialchars($enq['status']) ?></span>
              </td>
              <td>
                <a href="enquiries.php?id=<?= $enq['id'] ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.72rem;">View Message</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
