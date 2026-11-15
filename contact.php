<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Contact & Host Concierge Inquiry Page
 */

declare(strict_types=1);

$activePage = 'contact';
$pageTitle = 'Contact & Concierge | The Love Shack — Savaneta, Aruba';
$pageDescription = 'Get in touch with The Love Shack host concierge in Savaneta, Aruba. Inquire about availability, private boat charters, or dining recommendations.';

require_once __DIR__ . '/includes/header.php';

$faqs = get_faqs();
?>

  <main>
    <!-- Page Hero Banner -->
    <section class="page-banner" aria-label="Contact Concierge">
      <div class="page-banner-bg" role="img" aria-label="The Love Shack Villa Background"></div>
      <div class="page-banner-overlay"></div>
      <div class="page-banner-content">
        <span class="eyebrow light center">DIRECT HOST COMMUNICATION</span>
        <h1>Connect with <em>your concierge.</em></h1>
        <p>
          Whether inquiring about specific dates, airport transfers, or custom anniversary celebrations, our local team in Savaneta is here to assist.
        </p>
      </div>
    </section>

    <!-- Contact & FAQ Section -->
    <section class="contact-section wrap" aria-label="Contact Information and Form">
      <div class="contact-layout">
        <!-- Left: Concierge Info & FAQ -->
        <div class="contact-info-panel">
          <span class="eyebrow">ISLAND CONCIERGE</span>
          <h2>Local Aruban hospitality, <em>at your service.</em></h2>
          <p>
            We are dedicated to ensuring your vacation is seamless from first inquiry to departure. Connect directly with our on-island host for personal recommendations or customized stay requests.
          </p>

          <div class="contact-cards-grid">
            <div class="contact-card-item">
              <div class="contact-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </div>
              <div class="contact-card-content">
                <strong>DIRECT EMAIL</strong>
                <p><?= htmlspecialchars($settings['concierge_email'] ?? 'concierge@loveshackaruba.com') ?></p>
              </div>
            </div>

            <div class="contact-card-item">
              <div class="contact-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              </div>
              <div class="contact-card-content">
                <strong>PHONE & WHATSAPP</strong>
                <p><?= htmlspecialchars($settings['concierge_phone'] ?? '+297 580 0000') ?></p>
              </div>
            </div>

            <div class="contact-card-item">
              <div class="contact-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <div class="contact-card-content">
                <strong>VILLA LOCATION</strong>
                <p><?= htmlspecialchars($settings['property_location'] ?? 'Savaneta Beachfront, Aruba, Dutch Caribbean') ?></p>
              </div>
            </div>

            <div class="contact-card-item">
              <div class="contact-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 14 14"></polyline>
                </svg>
              </div>
              <div class="contact-card-content">
                <strong>HOST RESPONSE TIME</strong>
                <p>Typically within 2–4 hours (7 days a week)</p>
              </div>
            </div>
          </div>

          <!-- Dynamic FAQ Accordion from Database -->
          <div class="faq-wrap" id="faq">
            <h3>Frequently Asked Questions</h3>

            <?php foreach ($faqs as $idx => $faq): ?>
              <div class="faq-item <?= $idx === 0 ? 'active' : '' ?>">
                <button class="faq-question" type="button" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>">
                  <span><?= htmlspecialchars($faq['question']) ?></span>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-answer">
                  <p><?= htmlspecialchars($faq['answer']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Right: Inquiry Form -->
        <div class="form-card">
          <span class="eyebrow">SEND A MESSAGE</span>
          <h2>Direct Host Inquiry</h2>
          <p>Have a custom request or question? Send a direct message to our villa concierge.</p>

          <form id="contactForm" novalidate>
            <div class="form-group">
              <label for="contactName">Your Name *</label>
              <input type="text" id="contactName" name="name" placeholder="e.g. Michael Davies" required aria-required="true">
            </div>

            <div class="form-group-row">
              <div class="form-group">
                <label for="contactEmail">Email Address *</label>
                <input type="email" id="contactEmail" name="email" placeholder="michael@example.com" required aria-required="true">
              </div>
              <div class="form-group">
                <label for="contactPhone">Phone / WhatsApp</label>
                <input type="tel" id="contactPhone" name="phone" placeholder="+1 (555) 000-0000">
              </div>
            </div>

            <div class="form-group">
              <label for="contactSubject">Inquiry Topic</label>
              <select id="contactSubject" name="subject">
                <option value="General Question" selected>General Question</option>
                <option value="Booking Dates & Availability">Booking Dates & Availability</option>
                <option value="Concierge & Island Transfers">Concierge & Island Transfers</option>
                <option value="Special Occasion / Anniversary">Special Occasion / Anniversary</option>
              </select>
            </div>

            <div class="form-group">
              <label for="contactMessage">Your Message *</label>
              <textarea id="contactMessage" name="message" rows="5" placeholder="How can we assist in crafting your Aruban escape?" required aria-required="true"></textarea>
            </div>

            <div class="form-notice">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
              </svg>
              <p>
                Direct inquiries are handled by our dedicated Aruban villa concierge. We respect your privacy.
              </p>
            </div>

            <button type="submit" class="btn btn-teal" style="width: 100%;">
              Send Inquiry
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>

            <div class="form-feedback" id="contactFeedback" aria-live="polite"></div>
          </form>
        </div>
      </div>
    </section>

    <!-- Location Snapshot Banner -->
    <section class="location-banner wrap" style="margin-bottom: 80px;" aria-label="Savaneta Map Preview">
      <div class="map-card" style="background: var(--color-bg-aqua); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 40px 32px; text-align: center;">
        <span class="eyebrow center">COASTAL SANCTUARY</span>
        <h3 style="font-size: 1.8rem; margin-bottom: 12px; color: var(--color-primary);"><?= htmlspecialchars($property['name'] ?? 'The Love Shack') ?> · Savaneta</h3>
        <p style="max-width: 620px; margin: 0 auto 24px; color: var(--color-text-muted);">
          <?= htmlspecialchars($settings['property_location'] ?? 'Savaneta Beachfront, Aruba, Dutch Caribbean') ?> · Located 15 minutes south of Queen Beatrix Airport (AUA), walking distance to waterfront seafood dining at Zeerovers.
        </p>
        <a href="booking.php" class="btn btn-teal">
          Check Availability & Book Direct ↗
        </a>
      </div>
    </section>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
