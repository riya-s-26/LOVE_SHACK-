<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Reusable Footer Component
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$settings = get_site_settings();
$ext = '.php';
?>
  <!-- Multi-Column Luxury Footer -->
  <footer class="footer" aria-label="Site Footer">
    <div class="wrap">
      <div class="footer-grid">
        <!-- Brand Column -->
        <div class="footer-brand">
          <a class="logo" href="index<?= $ext ?>"><?= htmlspecialchars($settings['site_title'] ?? 'The Love Shack Aruba') ?></a>
          <p>
            An intimate luxury beachfront retreat in Savaneta, Aruba. Designed for barefoot relaxation, private pool days, and unhurried Caribbean memories.
          </p>
          <div class="footer-location-tag">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <?= htmlspecialchars($settings['property_location'] ?? 'Savaneta, Aruba · Dutch Caribbean') ?>
          </div>
        </div>

        <!-- Explore Navigation -->
        <div>
          <span class="footer-col-title">EXPLORE</span>
          <ul class="footer-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="property.php">The Property Overview</a></li>
            <li><a href="property.php#accommodations">Suites & Rooms</a></li>
            <li><a href="property.php#gallery">Photo Gallery</a></li>
            <li><a href="property.php#amenities">Curated Amenities</a></li>
            <li><a href="property.php#location">Savaneta Location</a></li>
          </ul>
        </div>

        <!-- Stay & Reservation Info -->
        <div>
          <span class="footer-col-title">YOUR STAY</span>
          <ul class="footer-links">
            <li><a href="booking.php">Check Availability</a></li>
            <li><a href="booking.php">Book Your Stay</a></li>
            <li><a href="contact.php">Host & Concierge</a></li>
            <li><a href="contact.php#faq">Frequently Asked Questions</a></li>
          </ul>
        </div>

        <!-- Direct Host Contact Card -->
        <div class="footer-cta-card">
          <h4>Reserve Direct</h4>
          <p>Connect with our Aruba concierge for personalized requests, airport transfers, and private bookings.</p>
          
          <div class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span><?= htmlspecialchars($settings['concierge_email'] ?? 'concierge@loveshackaruba.com') ?></span>
          </div>

          <div class="footer-contact-item" style="margin-bottom: 16px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            <span><?= htmlspecialchars($settings['concierge_phone'] ?? '+297 580 0000') ?> (WhatsApp)</span>
          </div>

          <a class="btn btn-teal" href="booking<?= $ext ?>" style="width: 100%; padding: 12px 18px; font-size: 0.75rem;">
            Book Your Stay ↗
          </a>
        </div>
      </div>

      <div class="footer-bottom">
        <span>© <span id="year"><?= date('Y') ?></span> <?= htmlspecialchars($settings['site_title'] ?? 'The Love Shack') ?>. All rights reserved. Aruba Vacation Rental.</span>
        <div class="footer-bottom-links">
          <span>Made for slow days by the sea.</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Lightbox Gallery Modal -->
  <div class="lightbox-modal" id="lightboxModal" role="dialog" aria-modal="true" aria-label="Photo Lightbox">
    <div class="lightbox-content">
      <button class="lightbox-close" aria-label="Close Lightbox">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
      <div class="lightbox-img-wrap">
        <img class="lightbox-img" src="assets/images/temporary-hero.jpg" alt="Villa expanded view">
      </div>
      <div class="lightbox-caption">
        <span class="lightbox-title">The Love Shack Villa</span>
      </div>
    </div>
  </div>

  <script src="assets/js/main.js?v=2.2"></script>
</body>
</html>
