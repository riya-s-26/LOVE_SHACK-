<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Dynamic Booking & Direct Reservation Engine
 */

declare(strict_types=1);

$activePage = 'booking';
$pageTitle = 'Book Your Stay | The Love Shack Aruba';
$pageDescription = 'Reserve your private luxury villa in Savaneta, Aruba. Direct booking inquiry for The Love Shack beachfront property with private freshwater pool.';

require_once __DIR__ . '/includes/header.php';

$property = get_property_details();
$services = get_additional_services();

// Group services
$groupedServices = [];
foreach ($services as $svc) {
    $cat = $svc['category'] ?? 'Other Extras';
    $groupedServices[$cat][] = $svc;
}
?>

  <main>
    <!-- Page Hero Banner -->
    <section class="page-banner" aria-label="Book Your Stay">
      <div class="page-banner-bg" role="img" aria-label="The Love Shack Villa Background"></div>
      <div class="page-banner-overlay"></div>
      <div class="page-banner-content">
        <span class="eyebrow light center">RESERVE YOUR STAY</span>
        <h1>Begin your island <em>escape.</em></h1>
        <p>
          Select your preferred dates and tailor your beachfront stay at The Love Shack in Savaneta, Aruba.
        </p>
      </div>
    </section>

    <!-- Multi-Step Booking Section -->
    <section class="booking-section wrap" aria-label="Direct Reservation Interface">
      
      <!-- Progress Bar (5 Steps) -->
      <div class="booking-steps-bar" role="tablist" aria-label="Booking steps">
        <div class="booking-step-item active" id="stepIndicator1">
          <div class="booking-step-num">1</div>
          <span>Dates & Guests</span>
        </div>
        <div class="booking-step-item" id="stepIndicator2">
          <div class="booking-step-num">2</div>
          <span>Rates & Summary</span>
        </div>
        <div class="booking-step-item" id="stepIndicator3">
          <div class="booking-step-num">3</div>
          <span>Customise Stay</span>
        </div>
        <div class="booking-step-item" id="stepIndicator4">
          <div class="booking-step-num">4</div>
          <span>Guest Details</span>
        </div>
        <div class="booking-step-item" id="stepIndicator5">
          <div class="booking-step-num">5</div>
          <span>Review & Submit</span>
        </div>
      </div>

      <div class="booking-layout">
        <!-- Left: Dynamic Booking Steps Flow -->
        <div class="form-card">
          <!-- STEP 1: Select Dates & Guests -->
          <div class="booking-step-pane active" id="paneStep1">
            <span class="eyebrow">STEP 1 OF 5</span>
            <h2>Select Dates & Guests</h2>
            <p>Choose your arrival and departure dates. The Love Shack requires a <?= (int)($settings['min_stay_nights'] ?? 3) ?>-night minimum stay (max <?= (int)($settings['max_guests'] ?? 6) ?> guests).</p>

            <div class="form-group-row">
              <div class="form-group">
                <label for="checkin">Check-in Date <span class="required-asterisk">*</span></label>
                <input type="date" id="checkin" name="checkin" required aria-required="true">
              </div>
              <div class="form-group">
                <label for="checkout">Check-out Date <span class="required-asterisk">*</span></label>
                <input type="date" id="checkout" name="checkout" required aria-required="true">
              </div>
            </div>

            <div class="form-group-row">
              <div class="form-group">
                <label for="adults">Adult Guests (13+) <span class="required-asterisk">*</span></label>
                <select id="adults" name="adults" required>
                  <option value="1">1 Adult</option>
                  <option value="2" selected>2 Adults</option>
                  <option value="3">3 Adults</option>
                  <option value="4">4 Adults</option>
                  <option value="5">5 Adults</option>
                  <option value="6">6 Adults (Maximum)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="children">Children (0–12)</label>
                <select id="children" name="children">
                  <option value="0" selected>0 Children</option>
                  <option value="1">1 Child</option>
                  <option value="2">2 Children</option>
                  <option value="3">3 Children</option>
                  <option value="4">4 Children</option>
                </select>
              </div>
            </div>

            <button type="button" class="btn btn-teal" id="btnCheckAvailability" style="width: 100%; margin-top: 10px;">
              Check Availability & Rates
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>

            <div class="form-feedback" id="feedbackStep1" aria-live="polite"></div>
          </div>

          <!-- STEP 2: Rates & Accommodation Summary -->
          <div class="booking-step-pane" id="paneStep2">
            <span class="eyebrow">STEP 2 OF 5 · RATES &amp; SUMMARY</span>
            <h2>Stay &amp; Rate Summary</h2>
            <p>Review the calculated rate breakdown for your selected dates and guest party.</p>

            <div class="review-section-box">
              <div class="review-row">
                <span>Property:</span>
                <strong><?= htmlspecialchars($property['name'] ?? 'The Love Shack') ?> (<?= htmlspecialchars($property['location_area'] ?? 'Savaneta, Aruba') ?>)</strong>
              </div>
              <div class="review-row">
                <span>Check-in:</span>
                <strong id="rateSummaryCheckin">—</strong>
              </div>
              <div class="review-row">
                <span>Check-out:</span>
                <strong id="rateSummaryCheckout">—</strong>
              </div>
              <div class="review-row">
                <span>Length of Stay:</span>
                <strong id="rateSummaryNights">—</strong>
              </div>
              <div class="review-row">
                <span>Guest Party:</span>
                <strong id="rateSummaryParty">—</strong>
              </div>
              <div class="review-row">
                <span>Nightly Rate:</span>
                <strong id="rateSummaryRate">—</strong>
              </div>
              <div class="review-row">
                <span>Accommodation Subtotal:</span>
                <strong id="rateSummarySubtotal">—</strong>
              </div>
              <div class="review-row">
                <span>Turnover & Cleaning Fee:</span>
                <strong id="rateSummaryCleaning">$<?= number_format((float)($settings['cleaning_fee'] ?? 180), 2) ?> USD</strong>
              </div>
              <div class="review-total-row">
                <span>Estimated Total:</span>
                <span id="rateSummaryGrandTotal" style="color: var(--color-primary);">$0.00 USD</span>
              </div>
            </div>

            <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
              <button type="button" class="btn btn-outline" id="btnBackToStep1" style="flex: 1;">
                &larr; Back
              </button>
              <button type="button" class="btn btn-teal" id="btnGoToStep3" style="flex: 1.5;">
                Next &rarr;
              </button>
              <button type="button" class="btn btn-outline" id="btnSkipExtrasFromStep2" style="flex: 1.2;">
                Skip Extras &rarr;
              </button>
            </div>
          </div>

          <!-- STEP 3: Customise Your Stay -->
          <div class="booking-step-pane" id="paneStep3">
            <span class="eyebrow">STEP 3 OF 5 · ENHANCE YOUR RETREAT</span>
            <h2>Customise Your Stay</h2>
            <p>Select bespoke on-island services and amenities to personalize your Aruban retreat.</p>

            <div id="servicesContainer">
              <?php foreach ($groupedServices as $catName => $catItems): ?>
                <div class="services-category-block">
                  <div class="services-category-title">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                    <?= htmlspecialchars($catName) ?>
                  </div>
                  <div class="service-cards-list">
                    <?php foreach ($catItems as $svc): ?>
                      <label class="service-addon-card" data-service-id="<?= (int)$svc['id'] ?>" data-service-name="<?= htmlspecialchars($svc['name']) ?>" data-service-price="<?= (float)$svc['price'] ?>">
                        <input type="checkbox" class="service-checkbox" value="<?= (int)$svc['id'] ?>" style="width: auto;">
                        <div class="service-addon-info">
                          <h4><?= htmlspecialchars($svc['name']) ?></h4>
                          <p><?= htmlspecialchars($svc['description']) ?></p>
                        </div>
                        <div class="service-addon-pricing">
                          <span class="service-price-tag">+$<?= number_format((float)$svc['price'], 2) ?></span>
                          <small style="color: var(--color-text-muted); font-size: 0.72rem;"><?= htmlspecialchars(str_replace('_', ' ', $svc['pricing_type'] ?? 'per_stay')) ?></small>
                        </div>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Bottom Navigation: BACK -> NEXT -> SKIP EXTRAS -->
            <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
              <button type="button" class="btn btn-outline" id="btnBackToStep2" style="flex: 1;">
                &larr; Back
              </button>
              <button type="button" class="btn btn-teal" id="btnGoToStep3b" style="flex: 1.5;">
                Next &rarr;
              </button>
              <button type="button" class="btn btn-outline" id="btnSkipExtrasFromStep3" style="flex: 1.2;">
                Skip Extras &rarr;
              </button>
            </div>
          </div>

          <!-- STEP 3B: Additional Services & Island Experiences Screen -->
          <div class="booking-step-pane" id="paneStep3b">
            <span class="eyebrow">ADDITIONAL SERVICES · ISLAND EXPERIENCES</span>
            <h2>Additional Services &amp; Island Experiences</h2>
            <p>Select bespoke on-island services, transportation, and excursions to personalize your Aruban retreat.</p>

            <div id="additionalServicesContainer">
              <!-- 1. Convenience & Transportation -->
              <div class="services-category-block">
                <div class="services-category-title">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>
                  1. Convenience &amp; Transportation
                </div>
                <div class="service-cards-list">
                  <label class="service-addon-card" data-service-id="grocery-service" data-service-name="Grocery Service" data-service-price="50" data-service-display="+$50.00 / trip">
                    <input type="checkbox" class="service-checkbox" value="grocery-service" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Grocery Service</h4>
                      <p>Pre-arrival pantry and refrigerator grocery stocking so your villa is completely refreshed and ready when you arrive.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$50.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">per trip</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="airport-pickup" data-service-name="Airport Pickup" data-service-price="40" data-service-display="+$40.00 / pickup">
                    <input type="checkbox" class="service-checkbox" value="airport-pickup" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Airport Pickup</h4>
                      <p>Personalized greeting at Queen Beatrix International Airport (AUA) and direct, private transfer to The Love Shack in Savaneta.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$40.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">per pickup</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="private-driver" data-service-name="Private Driver" data-service-price="0" data-service-display="$30–$70 each way">
                    <input type="checkbox" class="service-checkbox" value="private-driver" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Private Driver</h4>
                      <p>Dedicated island chauffeur for dinners, nightlife, shopping, or custom excursions across Aruba.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">$30–$70</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">each way (by dest./timing)</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="car-van-rentals" data-service-name="Car and Van Rentals" data-service-price="0" data-service-display="Price on request">
                    <input type="checkbox" class="service-checkbox" value="car-van-rentals" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Car and Van Rentals</h4>
                      <p>Door-to-door vehicle delivery directly to The Love Shack. Compact cars, 4x4 SUVs, and spacious passenger vans available.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">On Request</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">tailored vehicle rates</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="daily-housekeeping" data-service-name="Daily Housekeeping" data-service-price="20" data-service-display="+$20.00 / hour">
                    <input type="checkbox" class="service-checkbox" value="daily-housekeeping" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Daily Housekeeping</h4>
                      <p>Professional villa refresh, bed styling, fresh bath and beach towels, and kitchen tidying during your stay.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$20.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">per hour</small>
                    </div>
                  </label>
                </div>
              </div>

              <!-- 2. Adventure & Activities -->
              <div class="services-category-block">
                <div class="services-category-title">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
                  2. Adventure &amp; Activities
                </div>
                <div class="service-cards-list">
                  <label class="service-addon-card" data-service-id="utv-rental-2" data-service-name="UTV Rental (2-Seater)" data-service-price="190" data-service-display="+$190.00 / 8 hrs">
                    <input type="checkbox" class="service-checkbox" value="utv-rental-2" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>UTV Rental – 2-Seater</h4>
                      <p>All-terrain 2-passenger utility vehicle for 8 hours of rugged exploration across Arikok National Park and the Natural Pool.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$190.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">8-hour rental</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="utv-rental-4" data-service-name="UTV Rental (4-Seater)" data-service-price="380" data-service-display="+$380.00 / 8 hrs">
                    <input type="checkbox" class="service-checkbox" value="utv-rental-4" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>UTV Rental – 4-Seater</h4>
                      <p>All-terrain 4-passenger utility vehicle for 8 hours of off-road family adventure, desert trails, and secret coastlines.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$380.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">8-hour rental</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="guided-hiking" data-service-name="Guided Hiking Tours" data-service-price="50" data-service-display="+$50.00 / person">
                    <input type="checkbox" class="service-checkbox" value="guided-hiking" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Guided Hiking Tours</h4>
                      <p>Private guided trail exploration through Arikok National Park, hidden sea caves, cactus valleys, and panoramic lookout summits.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$50.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">per person</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="fishing-charter" data-service-name="Private Fishing Charter" data-service-price="600" data-service-display="+$600.00 (up to 6)">
                    <input type="checkbox" class="service-checkbox" value="fishing-charter" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Private Fishing Charter</h4>
                      <p>Private deep-sea offshore sportfishing charter with licensed captain, tackle, bait, ice, and drinks for up to 6 people.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$600.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">for up to 6 people</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="photography" data-service-name="Professional Photography" data-service-price="0" data-service-display="Available on request">
                    <input type="checkbox" class="service-checkbox" value="photography" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Professional Photography</h4>
                      <p>Golden-hour beach, villa, or couples photoshoot in Savaneta with professional editing and high-resolution digital gallery.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">On Request</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">bespoke session</small>
                    </div>
                  </label>
                </div>
              </div>

              <!-- 3. Wellness & Relaxation -->
              <div class="services-category-block">
                <div class="services-category-title">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                  3. Wellness &amp; Relaxation
                </div>
                <div class="service-cards-list">
                  <label class="service-addon-card" data-service-id="beach-yoga" data-service-name="Beach Yoga" data-service-price="35" data-service-display="+$35.00 / person">
                    <input type="checkbox" class="service-checkbox" value="beach-yoga" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Beach Yoga</h4>
                      <p>Private 1-hour mindfulness and gentle flow yoga session directly on the tranquil beach overlooking Comandeurs Bay.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$35.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">per person (1 hour)</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="poolside-massage" data-service-name="Poolside Massage" data-service-price="140" data-service-display="+$140.00 / person">
                    <input type="checkbox" class="service-checkbox" value="poolside-massage" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Poolside Massage</h4>
                      <p>Relaxing 1-hour Swedish or deep-tissue massage administered on your private pool deck by certified Aruban spa therapists.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">+$140.00</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">per person (1 hour)</small>
                    </div>
                  </label>

                  <label class="service-addon-card" data-service-id="floating-massage" data-service-name="Serene Floating Massage" data-service-price="0" data-service-display="Available on request">
                    <input type="checkbox" class="service-checkbox" value="floating-massage" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Serene Floating Massage</h4>
                      <p>Sensory aqua-cradle floating massage and water relaxation experience in the calm shallows of the lagoon or pool.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">On Request</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">bespoke aqua session</small>
                    </div>
                  </label>
                </div>
              </div>

              <!-- 4. Dining & Special Experiences -->
              <div class="services-category-block">
                <div class="services-category-title">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                  4. Dining &amp; Special Experiences
                </div>
                <div class="service-cards-list">
                  <label class="service-addon-card" data-service-id="private-chef-experience" data-service-name="Private Chef Experience" data-service-price="0" data-service-display="$85–$140 per person">
                    <input type="checkbox" class="service-checkbox" value="private-chef-experience" style="width: auto;">
                    <div class="service-addon-info">
                      <h4>Private Chef</h4>
                      <p>Bespoke seaside dinner prepared fresh at the villa, including gourmet appetizers, fresh catch or tenderloin entree, and artisan dessert.</p>
                    </div>
                    <div class="service-addon-pricing">
                      <span class="service-price-tag">$85–$140</span>
                      <small style="color: var(--color-text-muted); font-size: 0.72rem;">per person (3 courses)</small>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Complimentary Gear Informational Box -->
            <div class="booking-info-card complimentary-gear-card">
              <div class="booking-info-header">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h20M2 12l5-5m-5 5l5 5M22 12l-5-5m5 5l-5 5"></path></svg>
                <div>
                  <h4>Complimentary Beach &amp; Water Sports Gear</h4>
                  <span class="info-badge">Included with your stay</span>
                </div>
              </div>
              <p>All guests enjoy complimentary, unlimited access to our ocean kayaks, stand-up paddleboards (SUP), and snorkeling gear (masks, snorkels &amp; fins) to explore Savaneta lagoon and Sea Glass Island at no additional charge.</p>
            </div>

            <!-- Arrival Information Requirements -->
            <div class="booking-info-card arrival-info-card">
              <div class="booking-info-header">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <div>
                  <h4>Arrival Information &amp; Pre-Stay Requirements</h4>
                  <span class="info-badge requirement">Informational Requirement</span>
                </div>
              </div>
              <ul class="arrival-requirements-list">
                <li>
                  <strong>WhatsApp Communication:</strong> Check-in details, villa gate access codes, and arrival directions are coordinated directly via WhatsApp (+297 580 0000). Please provide a WhatsApp-enabled phone number on the next step.
                </li>
                <li>
                  <strong>Flight Number Details:</strong> We kindly request your arriving flight number and landing schedule so our on-island team can track delays and greet you right as you arrive.
                </li>
              </ul>
            </div>

            <!-- Outside Vendor Pricing Disclaimer -->
            <div class="vendor-notice-banner">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
              <span><strong>Please Note:</strong> Outside vendor prices, vehicle rentals, and private activity rates are subject to seasonal supplier availability and may change. Your concierge will confirm final schedules and exact quotes directly with you.</span>
            </div>

            <!-- Bottom Navigation: BACK -> NEXT -> SKIP EXTRAS -->
            <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
              <button type="button" class="btn btn-outline" id="btnBackToStep3" style="flex: 1;">
                &larr; Back
              </button>
              <button type="button" class="btn btn-teal" id="btnGoToStep4" style="flex: 1.5;">
                Next &rarr;
              </button>
              <button type="button" class="btn btn-outline" id="btnSkipExtrasFromStep3b" style="flex: 1.2;">
                Skip Extras &rarr;
              </button>
            </div>
          </div>
          </div>

          <!-- STEP 4: Guest Details Form -->
          <div class="booking-step-pane" id="paneStep4">
            <span class="eyebrow">STEP 4 OF 5 · GUEST DETAILS</span>
            <h2>Guest &amp; Contact Details</h2>
            <p>Please enter your contact details and any special requests so our concierge can coordinate your stay.</p>

            <div class="form-group-row">
              <div class="form-group">
                <label for="firstName">First Name <span class="required-asterisk">*</span></label>
                <input type="text" id="firstName" name="firstName" placeholder="e.g. Sarah" required>
              </div>
              <div class="form-group">
                <label for="lastName">Last Name <span class="required-asterisk">*</span></label>
                <input type="text" id="lastName" name="lastName" placeholder="e.g. Miller" required>
              </div>
            </div>

            <div class="form-group-row">
              <div class="form-group">
                <label for="guestEmail">Email Address <span class="required-asterisk">*</span></label>
                <input type="email" id="guestEmail" name="guestEmail" placeholder="sarah@example.com" required>
              </div>
              <div class="form-group">
                <label for="guestPhone">Phone / WhatsApp <span class="required-asterisk">*</span></label>
                <input type="tel" id="guestPhone" name="guestPhone" placeholder="+1 (555) 000-0000" required>
              </div>
            </div>

            <div class="form-group">
              <label for="guestCountry">Country / Territory</label>
              <input type="text" id="guestCountry" name="guestCountry" placeholder="e.g. United States, Canada, Netherlands">
            </div>

            <div class="form-group">
              <label for="specialRequests">Special Requests, Occasion or Arrival Notes (Optional)</label>
              <textarea id="specialRequests" name="specialRequests" rows="3" placeholder="Tell us if you are celebrating an anniversary, require airport coordination, or have dietary preferences..."></textarea>
            </div>

            <div class="form-feedback" id="feedbackStep4" aria-live="polite"></div>

            <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
              <button type="button" class="btn btn-outline" id="btnBackFromStep4" style="flex: 1;">
                &larr; Back to Services
              </button>
              <button type="button" class="btn btn-teal" id="btnGoToStep5" style="flex: 2;">
                Review Booking Details &rarr;
              </button>
            </div>
          </div>

          <!-- STEP 5: Review & Submit Booking Request -->
          <div class="booking-step-pane" id="paneStep5">
            <span class="eyebrow">STEP 5 OF 5 · REVIEW RESERVATION</span>
            <h2>Review Reservation Request</h2>
            <p>Please review your dates, guest details, and stay summary before submitting your reservation request.</p>

            <div class="review-section-box">
              <h3>
                <span>Dates &amp; Accommodation</span>
                <button type="button" id="editStepDatesBtn" class="btn-text-link" style="font-size: 0.72rem;">Edit Dates</button>
              </h3>
              <div class="review-row">
                <span>Villa:</span>
                <strong><?= htmlspecialchars($property['name'] ?? 'The Love Shack') ?> (<?= htmlspecialchars($property['location_area'] ?? 'Savaneta, Aruba') ?>)</strong>
              </div>
              <div class="review-row">
                <span>Stay Dates:</span>
                <strong id="revDates">—</strong>
              </div>
              <div class="review-row">
                <span>Total Duration:</span>
                <strong id="revDuration">—</strong>
              </div>
              <div class="review-row">
                <span>Guests:</span>
                <strong id="revGuests">—</strong>
              </div>
              <div class="review-row">
                <span>Base Accommodation:</span>
                <strong id="revAccommodation">$0.00 USD</strong>
              </div>
              <div class="review-row">
                <span>Cleaning &amp; Turnover Fee:</span>
                <strong id="revCleaning">$<?= number_format((float)($settings['cleaning_fee'] ?? 180), 2) ?> USD</strong>
              </div>
            </div>

            <div class="review-section-box" id="revServicesBox" style="display: none;">
              <h3>
                <span>Custom Stay Extras</span>
                <button type="button" id="editStepServicesBtn" class="btn-text-link" style="font-size: 0.72rem;">Edit Extras</button>
              </h3>
              <div id="revServicesList"></div>
            </div>

            <div class="review-section-box">
              <h3>
                <span>Guest Details</span>
                <button type="button" id="editStepGuestBtn" class="btn-text-link" style="font-size: 0.72rem;">Edit Contact</button>
              </h3>
              <div class="review-row">
                <span>Name:</span>
                <strong id="revGuestName">—</strong>
              </div>
              <div class="review-row">
                <span>Email:</span>
                <strong id="revGuestEmail">—</strong>
              </div>
              <div class="review-row">
                <span>Phone / WhatsApp:</span>
                <strong id="revGuestPhone">—</strong>
              </div>
              <div class="review-row" id="revGuestCountryRow" style="display: none;">
                <span>Country:</span>
                <strong id="revGuestCountry">—</strong>
              </div>
              <div class="review-row" id="revNotesRow" style="display: none;">
                <span>Special Requests:</span>
                <span id="revSpecialRequests" style="color: var(--color-text-muted); font-style: italic;">—</span>
              </div>
            </div>

            <div class="review-section-box" style="background: #fafafa; border-color: var(--color-secondary);">
              <div class="review-total-row" style="border: none; padding: 0; font-size: 1.35rem;">
                <span>Estimated Total:</span>
                <span id="revGrandTotal" style="color: var(--color-primary);">$0.00 USD</span>
              </div>
            </div>

            <div class="form-notice">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
              </svg>
              <p>
                <strong>Reservation Request:</strong> Direct bookings are confirmed by our concierge within 24 hours. No payment is charged today.
              </p>
            </div>

            <div class="form-feedback" id="feedbackStep5" aria-live="polite"></div>

            <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
              <button type="button" class="btn btn-outline" id="btnBackToStep4" style="flex: 1;">
                &larr; Back
              </button>
              <button type="button" class="btn btn-teal" id="btnSubmitBookingRequest" style="flex: 2;">
                Submit Reservation Request
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </button>
            </div>
          </div>

        </div>

        <!-- Right: Live Synchronized Booking Summary Card -->
        <aside class="booking-summary-card" aria-label="Reservation Summary">
          <div class="summary-villa-header">
            <div class="summary-thumb">
              <img src="<?= htmlspecialchars($property['hero_image'] ?? 'assets/images/temporary-hero.jpg') ?>" alt="The Love Shack Villa">
            </div>
            <div class="summary-header-text">
              <h3><?= htmlspecialchars($property['name'] ?? 'The Love Shack Villa') ?></h3>
              <span><?= htmlspecialchars($property['location_area'] ?? 'Savaneta Beachfront, Aruba') ?></span>
            </div>
          </div>

          <div class="summary-dates-box">
            <div class="summary-date-row">
              <span>CHECK-IN</span>
              <span id="sumCheckin">Not selected</span>
            </div>
            <div class="summary-date-row">
              <span>CHECK-OUT</span>
              <span id="sumCheckout">Not selected</span>
            </div>
            <div class="summary-date-row">
              <span>DURATION</span>
              <span id="sumNights">—</span>
            </div>
            <div class="summary-date-row">
              <span>PARTY</span>
              <span id="sumGuests">2 Guests</span>
            </div>
          </div>

          <div class="summary-pricing-row">
            <div>
              <strong>Estimated Total</strong>
              <div id="sumPricingSubcaption" style="font-size: 0.72rem; color: var(--color-text-muted);">From $<?= number_format((float)($settings['default_nightly_rate'] ?? 650), 0) ?> / night</div>
            </div>
            <span id="sumTotal">From $<?= number_format((float)($settings['default_nightly_rate'] ?? 650), 0) ?> / night</span>
          </div>

          <span class="footer-col-title" style="color: var(--color-primary); margin-bottom: 12px;">INCLUDED WITH YOUR STAY:</span>
          <ul class="summary-inclusions">
            <li>
              <span class="inclusion-dot"></span>
              <span>Exclusive access to private swimming pool</span>
            </li>
            <li>
              <span class="inclusion-dot"></span>
              <span>Direct beachfront access steps from veranda</span>
            </li>
            <li>
              <span class="inclusion-dot"></span>
              <span>High-speed Wi-Fi & 4K smart entertainment</span>
            </li>
            <li>
              <span class="inclusion-dot"></span>
              <span>Beach chairs, beach towels & cooler sets</span>
            </li>
            <li>
              <span class="inclusion-dot"></span>
              <span>Dedicated pre-arrival island concierge support</span>
            </li>
          </ul>

          <div class="summary-contact-help">
            Prefer to speak directly? <br>
            <a href="contact.php">Message host on WhatsApp: <?= htmlspecialchars($settings['concierge_phone'] ?? '+297 580 0000') ?> ↗</a>
          </div>
        </aside>
      </div>
    </section>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
