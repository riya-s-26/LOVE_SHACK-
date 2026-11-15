<?php
/**
 * THE LOVE SHACK — Admin Panel Shared Header
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$settings = get_site_settings();
$adminPage = $adminPage ?? 'dashboard';
$adminUser = $_SESSION['admin_name'] ?? 'Administrator';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($adminTitle ?? 'Admin Dashboard') ?> | The Love Shack Management</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@700&family=Montserrat:wght@400;500;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../assets/css/style.css?v=2.2">
  <style>
    /* Admin Area Clean Executive Dashboard Styles */
    body.admin-body {
      background-color: #f4f8f8;
      font-family: var(--font-body);
      color: var(--color-text-body);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .admin-navbar {
      background: var(--color-white);
      border-bottom: 1px solid var(--color-border);
      padding: 14px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 10px rgba(22, 78, 99, 0.04);
    }
    .admin-brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .admin-brand .logo {
      font-size: 1.4rem;
      color: var(--color-primary);
    }
    .admin-brand-tag {
      font-family: var(--font-ui);
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      background: #eef6f6;
      color: var(--color-secondary);
      padding: 4px 10px;
      border-radius: var(--radius-pill);
    }
    .admin-nav-links {
      display: flex;
      align-items: center;
      gap: 18px;
      overflow-x: auto;
    }
    .admin-nav-links a {
      font-family: var(--font-ui);
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--color-text-body);
      padding: 8px 12px;
      border-radius: 8px;
      transition: all var(--transition-fast);
      white-space: nowrap;
    }
    .admin-nav-links a:hover,
    .admin-nav-links a.active {
      color: var(--color-primary);
      background: var(--color-bg-aqua);
    }
    .admin-nav-links a.active {
      font-weight: 700;
      color: var(--color-secondary);
    }
    .admin-user-menu {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .admin-user-pill {
      font-family: var(--font-ui);
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--color-text-muted);
    }
    .admin-container {
      max-width: 1240px;
      width: 94%;
      margin: 32px auto;
      flex: 1;
    }
    .admin-header-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 16px;
    }
    .admin-header-row h1 {
      font-size: 2rem;
      color: var(--color-primary);
      margin-bottom: 4px;
    }
    .admin-header-row p {
      color: var(--color-text-muted);
      font-size: 0.95rem;
    }
    /* Stat cards */
    .admin-stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 32px;
    }
    .admin-stat-card {
      background: var(--color-white);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      padding: 22px 24px;
      box-shadow: var(--shadow-sm);
    }
    .admin-stat-label {
      font-family: var(--font-ui);
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--color-text-muted);
      margin-bottom: 8px;
    }
    .admin-stat-value {
      font-family: var(--font-ui);
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--color-primary);
      line-height: 1;
    }
    .admin-stat-card.highlight {
      border-left: 4px solid var(--color-secondary);
    }
    .admin-stat-card.alert {
      border-left: 4px solid #e09848;
    }
    /* Data tables */
    .admin-card {
      background: var(--color-white);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      padding: 28px;
      box-shadow: var(--shadow-sm);
      margin-bottom: 32px;
    }
    .admin-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 14px;
      border-bottom: 1px solid var(--color-border-light);
    }
    .admin-card-header h2 {
      font-size: 1.4rem;
      color: var(--color-primary);
      margin: 0;
    }
    .admin-table-wrap {
      overflow-x: auto;
    }
    table.admin-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.88rem;
    }
    table.admin-table th {
      font-family: var(--font-ui);
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--color-text-muted);
      text-align: left;
      padding: 12px 14px;
      border-bottom: 2px solid var(--color-border);
      background: #fafdfd;
    }
    table.admin-table td {
      padding: 14px;
      border-bottom: 1px solid var(--color-border-light);
      vertical-align: middle;
    }
    table.admin-table tr:hover {
      background: #fbfdfd;
    }
    /* Status badges */
    .badge {
      font-family: var(--font-ui);
      font-size: 0.68rem;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: var(--radius-pill);
      display: inline-block;
      text-transform: uppercase;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-confirmed { background: #d1fae5; color: #065f46; }
    .badge-declined { background: #fee2e2; color: #991b1b; }
    .badge-cancelled { background: #f3f4f6; color: #374151; }
    .badge-new { background: #e0f2fe; color: #0369a1; }
    .badge-read { background: #f3f4f6; color: #4b5563; }
    .badge-responded { background: #dcfce7; color: #166534; }
    /* Form controls in admin */
    .admin-form-group {
      margin-bottom: 20px;
    }
    .admin-form-group label {
      display: block;
      font-family: var(--font-ui);
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--color-primary);
      margin-bottom: 8px;
    }
    .admin-form-group input,
    .admin-form-group select,
    .admin-form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-sm);
      font-family: var(--font-body);
      font-size: 0.95rem;
      color: var(--color-text-body);
      background: var(--color-white);
      transition: border-color var(--transition-fast);
    }
    .admin-form-group input:focus,
    .admin-form-group select:focus,
    .admin-form-group textarea:focus {
      outline: none;
      border-color: var(--color-secondary);
      box-shadow: 0 0 0 3px rgba(49, 157, 160, 0.15);
    }
    .admin-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .admin-grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    @media (max-width: 768px) {
      .admin-grid-2, .admin-grid-3 {
        grid-template-columns: 1fr;
      }
      .admin-navbar {
        flex-direction: column;
        align-items: stretch;
        gap: 14px;
      }
    }
  </style>
</head>
<body class="admin-body">

  <nav class="admin-navbar">
    <div class="admin-brand">
      <a class="logo" href="dashboard.php"><?= htmlspecialchars($settings['site_title'] ?? 'The Love Shack') ?></a>
      <span class="admin-brand-tag">Admin Console</span>
    </div>

    <div class="admin-nav-links">
      <a class="<?= $adminPage === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
      <a class="<?= $adminPage === 'bookings' ? 'active' : '' ?>" href="bookings.php">Bookings</a>
      <a class="<?= $adminPage === 'enquiries' ? 'active' : '' ?>" href="enquiries.php">Enquiries</a>
      <a class="<?= $adminPage === 'content' ? 'active' : '' ?>" href="content.php">Content</a>
      <a class="<?= $adminPage === 'services' ? 'active' : '' ?>" href="services.php">Services</a>
      <a class="<?= $adminPage === 'pricing' ? 'active' : '' ?>" href="pricing.php">Pricing</a>
      <a class="<?= $adminPage === 'gallery' ? 'active' : '' ?>" href="gallery.php">Gallery</a>
      <a class="<?= $adminPage === 'amenities' ? 'active' : '' ?>" href="amenities.php">Amenities</a>
    </div>

    <div class="admin-user-menu">
      <span class="admin-user-pill">Logged in: <strong><?= htmlspecialchars($adminUser) ?></strong></span>
      <a href="../index.php" target="_blank" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.72rem;">View Site ↗</a>
      <a href="logout.php" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.72rem; border-color: #fecaca; color: #b91c1c;">Logout</a>
    </div>
  </nav>

  <div class="admin-container">
