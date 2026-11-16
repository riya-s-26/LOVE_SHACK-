<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Reusable Header Component
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$settings = get_site_settings();
$pageTitle = $pageTitle ?? ($settings['site_title'] . ' | ' . $settings['site_tagline']);
$pageDescription = $pageDescription ?? 'Private luxury beachfront villa in Savaneta, Aruba with private swimming pool and direct ocean access.';
$activePage = $activePage ?? 'home';

// Ensure production pages always use .php
$ext = '.php';
$roomsLink = ($activePage === 'home') ? '#rooms' : 'property.php#accommodations';
$galleryLink = ($activePage === 'home') ? '#gallery' : 'property.php#gallery';
$amenitiesLink = ($activePage === 'home') ? '#amenities' : 'property.php#amenities';
$locationLink = ($activePage === 'home') ? '#location' : 'property.php#location';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  
  <!-- Typography System: Bodoni Moda (Logo), Cormorant Garamond (Headings), Lato (Body), Montserrat (Nav & Buttons) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="assets/css/style.css?v=2.2">
</head>
<body>

  <!-- Floating Luxury Navigation Bar -->
  <div class="header-wrapper">
    <header class="header">
      <a class="logo" href="index<?= $ext ?>" aria-label="The Love Shack Aruba Homepage"><?= htmlspecialchars($settings['site_title'] ?? 'The Love Shack Aruba') ?></a>
      
      <button class="menu-btn" aria-label="Toggle navigation menu" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>

      <nav class="nav" aria-label="Main Navigation">
        <a class="<?= $activePage === 'home' ? 'current' : '' ?>" href="index.php">Home</a>
        <a class="<?= $activePage === 'property' ? 'current' : '' ?>" href="property.php">The Property</a>
        <a class="<?= $activePage === 'booking' ? 'current' : '' ?>" href="booking.php">Book Your Stay</a>
        <a class="<?= $activePage === 'contact' ? 'current' : '' ?>" href="contact.php">Contact</a>
        <a class="nav-cta" href="booking.php">
          Check Availability
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="7" y1="17" x2="17" y2="7"></line>
            <polyline points="7 7 17 7 17 17"></polyline>
          </svg>
        </a>
      </nav>
    </header>
  </div>
