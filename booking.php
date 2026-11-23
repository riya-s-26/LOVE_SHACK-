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
              <button type="button" class="btn btn-teal" id="btnGoToStep3" style="flex: 2;">
                Next &rarr;
              </button>
            </div>
          </div>

          <!-- STEP 3: Customise Your Stay (Enquiry-Only Add-ons) -->
                    <!-- STEP 3: Customise Your Stay (Enquiry-Only Add-ons) -->
          <div class="booking-step-pane" id="paneStep3">
            <span class="eyebrow">BESPOKE ISLAND CONCIERGE</span>
            <h2>Customise Your Stay</h2>
            <div class="concierge-lead-quote">
              “We look forward to hosting you and making all of your Aruba vacation dreams come true!”
            </div>
            <p>We strive to offer our guests the best experience and have created a list of custom services.</p>

            <div class="concierge-enquiry-banner">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
              </svg>
              <p>Select the services and experiences you may be interested in during your stay. Our concierge team will contact you to discuss availability, arrangements and final pricing.</p>
            </div>

            <!-- GROUP 1: Arrival & Transportation -->
            <div class="services-category-block">
              <div class="services-category-title">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                1. Arrival &amp; Transportation
              </div>

              <!-- Airport Pickup -->
              <div class="service-addon-featured service-addon-card" data-service-id="airport-pickup" data-service-name="Airport Pickup" data-service-price="0" data-service-display="$40 USD per pickup">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_airport_pickup" value="airport-pickup">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/champagne-ice-bucket.jpg" alt="Airport greeting and transfer" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Airport Pickup</h4>
                      <span class="service-price-badge">$40 USD per pickup</span>
                    </div>
                    <p>Enjoy convenient airport pickup from Queen Beatrix International Airport directly to The Love Shack.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      $40 per pickup time · Multiple arrivals require separate pickups · Paid in cash to Alexandra upon arrival
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="airportPickupGroups">Number of Pickup Times / Groups</label>
                      <select id="airportPickupGroups" name="airportPickupGroups">
                        <option value="1 pickup group ($40)">1 Pickup (All guests arriving together)</option>
                        <option value="2 pickup groups ($80)">2 Separate Arrival Pickups ($40 each)</option>
                        <option value="3+ pickup groups">3+ Separate Arrival Pickups</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="airportPickupNotes">Arrival Notes &amp; Flight Timing</label>
                      <input type="text" id="airportPickupNotes" name="airportPickupNotes" placeholder="e.g. Group 1 arriving 1:45 PM, Group 2 arriving 5:20 PM...">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Private Driver to Dinner -->
              <div class="service-addon-featured service-addon-card" data-service-id="private-driver" data-service-name="Private Driver to Dinner" data-service-price="0" data-service-display="$30–$70 each-way">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_private_driver" value="private-driver">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/savaneta-sunset-boat.jpg" alt="Private island driver for dining" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Private Driver to Dinner</h4>
                      <span class="service-price-badge">$30–$70 each-way</span>
                    </div>
                    <p>Private chauffeured transportation for dining, beach outings, and nightlife across Aruba. All destination prices are specified as each-way rates.</p>
                    <table class="service-rates-table">
                      <thead>
                        <tr>
                          <th>Destination Area</th>
                          <th>Each-Way Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td>San Nicolas</td><td><strong>$30</strong></td></tr>
                        <tr><td>Eagle Beach area</td><td><strong>$40</strong></td></tr>
                        <tr><td>Palm Beach &amp; Lighthouse</td><td><strong>$50</strong></td></tr>
                        <tr><td>After midnight transportation</td><td><strong>$70</strong></td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="privateDriverDestination">Preferred Primary Destination</label>
                      <select id="privateDriverDestination" name="privateDriverDestination">
                        <option value="San Nicolas ($30 each-way)">San Nicolas ($30 each-way)</option>
                        <option value="Eagle Beach area ($40 each-way)">Eagle Beach area ($40 each-way)</option>
                        <option value="Palm Beach & Lighthouse ($50 each-way)">Palm Beach &amp; Lighthouse ($50 each-way)</option>
                        <option value="After midnight ($70 each-way)">After midnight destination ($70 each-way)</option>
                        <option value="Multiple / Custom Dining Outings">Multiple / Custom Dining Outings (Coordinate with concierge)</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="privateDriverNotes">Transportation Requests &amp; Dining Plans</label>
                      <textarea id="privateDriverNotes" name="privateDriverNotes" rows="2" placeholder="Tell us if you have reservations (e.g., Flying Fishbone, Zeerovers, Papiamento), desired dates or times..."></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Car & Van Rentals -->
              <div class="service-addon-featured service-addon-card" data-service-id="car-van-rental" data-service-name="Car & Van Rentals" data-service-price="0" data-service-display="Price upon request">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_car_rental" value="car-van-rental">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/divi-tree-beach.jpg" alt="Car rental delivered to villa" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Car &amp; Van Rentals</h4>
                      <span class="service-enquiry-badge">Enquiry Only · Price upon request</span>
                    </div>
                    <p>Arrange a convenient car or van rental, delivered directly to The Love Shack so your vehicle is ready when you arrive. Avoid waiting in lines at rental shops.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Door-to-door villa delivery &amp; return · Rate upon request based on vehicle &amp; dates
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label>Vehicle Type Preference</label>
                      <div class="opt-checkboxes">
                        <label class="opt-checkbox-item"><input type="checkbox" id="chkPrefCar" checked> Car Rental</label>
                        <label class="opt-checkbox-item"><input type="checkbox" id="chkPrefVan"> Van Rental</label>
                      </div>
                    </div>
                    <div class="opt-group">
                      <label for="rentalVehiclePref">Preferred Model Category</label>
                      <select id="rentalVehiclePref" name="rentalVehiclePref">
                        <option value="Economical Compact Car">Economical Compact Car</option>
                        <option value="Mid-size Sedan">Mid-size Sedan</option>
                        <option value="Rugged 4x4 SUV">Rugged 4x4 SUV</option>
                        <option value="7-Passenger Van">7-Passenger Van</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="rentalRequirements">Additional Requirements / Rental Duration</label>
                      <textarea id="rentalRequirements" name="rentalRequirements" rows="2" placeholder="e.g. Full stay duration, child safety seats, delivery timing..."></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- GROUP 2: Grocery & Housekeeping -->
            <div class="services-category-block">
              <div class="services-category-title">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                2. Grocery &amp; Housekeeping
              </div>

              <!-- Grocery Service -->
              <div class="service-addon-featured service-addon-card" data-service-id="grocery-service" data-service-name="Grocery Service" data-service-price="0" data-service-display="$50 per trip + groceries">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_grocery" value="grocery-service">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/champagne-ice-bucket.jpg" alt="Villa grocery provisioning" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Grocery Service</h4>
                      <span class="service-price-badge">$50 per trip</span>
                    </div>
                    <p>Have your favorite groceries ready when you arrive. Provide your shopping list, including groceries, beverages and alcohol. Our on-site co-host and house concierge, Alexandra, will shop for you and stock the house before your arrival.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      <strong>Important:</strong> $50 is the shopping service fee per trip. Grocery purchases are an additional expense. Alexandra fronts the grocery expense, and you reimburse the expense in cash upon arrival with the store receipt. Additional shopping trips during your stay can also be arranged.
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="groceryList">Your Grocery Shopping List (Groceries, Beverages &amp; Alcohol)</label>
                      <textarea id="groceryList" name="groceryList" rows="3" placeholder="Enter desired items: e.g. Balashi beer, chilled white wine, bottled water, tropical fruits, eggs, milk, snacks, coffee beans..."></textarea>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="groceryNotes">Special Instructions or Brand Preferences</label>
                      <input type="text" id="groceryNotes" name="groceryNotes" placeholder="e.g. Oat milk preference, organic produce, preferred beer/wine labels...">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Daily Housekeeping -->
              <div class="service-addon-featured service-addon-card" data-service-id="housekeeping" data-service-name="Daily Housekeeping" data-service-price="0" data-service-display="$20 per hour">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_housekeeping" value="housekeeping">
                  <div class="service-addon-thumb">
                    <img src="assets/images/rooms/sea-esta-1.jpg" alt="Housekeeping and villa refresh" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Daily Housekeeping</h4>
                      <span class="service-price-badge">$20 per hour</span>
                    </div>
                    <p>Enjoy additional housekeeping services during your stay, including daily cleaning and villa refreshes. Housekeeping is an optional additional service.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Flexible scheduling · Daily bed dressing, bathroom sanitizing, and towel refreshes
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="housekeepingHours">Requested Hours Per Day</label>
                      <select id="housekeepingHours" name="housekeepingHours">
                        <option value="1 hour / day ($20/day)">1 Hour / Day ($20 / day)</option>
                        <option value="2 hours / day ($40/day)" selected>2 Hours / Day ($40 / day)</option>
                        <option value="3 hours / day ($60/day)">3 Hours / Day ($60 / day)</option>
                        <option value="4 hours / day ($80/day)">4 Hours / Day ($80 / day)</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="housekeepingFrequency">Preferred Frequency</label>
                      <select id="housekeepingFrequency" name="housekeepingFrequency">
                        <option value="Daily throughout stay" selected>Daily Throughout Stay</option>
                        <option value="Every other day">Every Other Day</option>
                        <option value="Mid-stay deep refresh">Mid-Stay Deep Refresh</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="housekeepingNotes">Optional Housekeeping Instructions</label>
                      <input type="text" id="housekeepingNotes" name="housekeepingNotes" placeholder="e.g. Midday while at beach, extra pool towel laundry, special attention to master suite...">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- GROUP 3: Wellness & Experiences -->
            <div class="services-category-block">
              <div class="services-category-title">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
                3. Wellness &amp; Experiences
              </div>

              <!-- Poolside Massage Under The Palapa -->
              <div class="service-addon-featured service-addon-card" data-service-id="poolside-massage" data-service-name="Poolside Massage Under the Palapa" data-service-price="0" data-service-display="$140 per person – 1 hour">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_poolside_massage" value="poolside-massage">
                  <div class="service-addon-thumb">
                    <img src="assets/images/services/private-massage-treatment.jpg" alt="Poolside massage under palapa" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Poolside Massage Under the Palapa</h4>
                      <span class="service-price-badge">$140 per person – 1 hour</span>
                    </div>
                    <p>Enjoy a restorative one-hour massage under the beautiful poolside palapa. Available for individual guests or couples, with one person receiving treatment at a time.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Certified massage therapists · Rates may change based on specific treatment selected
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="massageFormat">Format Preference</label>
                      <select id="massageFormat" name="massageFormat">
                        <option value="Individual (1 person, 1 hour)">Individual Guest (1 hour session)</option>
                        <option value="Couples (Back-to-back sequential)">Couples (Back-to-back sequential treatment)</option>
                        <option value="Multiple guests">Multiple Guests</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="massageParticipants">Number of Participants</label>
                      <select id="massageParticipants" name="massageParticipants">
                        <option value="1">1 Person ($140)</option>
                        <option value="2" selected>2 Persons ($280)</option>
                        <option value="3">3 Persons</option>
                        <option value="4+">4+ Persons</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="massageDate">Preferred Date or Time</label>
                      <input type="text" id="massageDate" name="massageDate" placeholder="e.g. Day 2 afternoon, 4:00 PM...">
                    </div>
                    <div class="opt-group">
                      <label for="massageNotes">Treatment Focus / Requests</label>
                      <input type="text" id="massageNotes" name="massageNotes" placeholder="e.g. Deep tissue, Swedish relaxation, aromatherapy...">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Serene Floating Massage (Distinct Card) -->
              <div class="service-addon-featured service-addon-card" data-service-id="floating-massage" data-service-name="Serene Floating Massage" data-service-price="0" data-service-display="Enquiry Only – Price to be confirmed">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_floating_massage" value="floating-massage">
                  <div class="service-addon-thumb">
                    <img src="assets/images/services/floating-massage-lagoon.jpg" alt="Overwater floating massage experience" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Serene Floating Massage</h4>
                      <span class="service-enquiry-badge">Enquiry Only · Price to be confirmed</span>
                    </div>
                    <p>Experience a gorgeous floating massage setting right outside The Love Shack. Enjoy a peaceful sunset massage surrounded by Aruba's beautiful turquoise waters.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Alexandra can arrange your booking. Ask for Angie!
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="floatingMassageParticipants">Number of Participants</label>
                      <select id="floatingMassageParticipants" name="floatingMassageParticipants">
                        <option value="1">1 Person</option>
                        <option value="2" selected>2 Persons</option>
                        <option value="3+">3+ Persons</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="floatingMassageDate">Preferred Date &amp; Time</label>
                      <input type="text" id="floatingMassageDate" name="floatingMassageDate" placeholder="e.g. Sunset session around 5:30 PM...">
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="floatingMassageNotes">Special Notes for Alexandra &amp; Angie</label>
                      <textarea id="floatingMassageNotes" name="floatingMassageNotes" rows="2" placeholder="Tell us if this is for an anniversary, birthday, or specific massage style..."></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Yoga on the Beach -->
              <div class="service-addon-featured service-addon-card" data-service-id="yoga-beach" data-service-name="Yoga on the Beach" data-service-price="0" data-service-display="$35 per person">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_yoga" value="yoga-beach">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/baby-beach.jpg" alt="Yoga on Aruba beach" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Yoga on the Beach</h4>
                      <span class="service-price-badge">$35 per person</span>
                    </div>
                    <p>Enjoy a relaxing one-hour yoga session on the beautiful beaches of Aruba.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      1-hour beach yoga session · Suitable for all experience levels
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="yogaParticipants">Number of Participants</label>
                      <select id="yogaParticipants" name="yogaParticipants">
                        <option value="1">1 Person ($35)</option>
                        <option value="2" selected>2 Persons ($70)</option>
                        <option value="3">3 Persons ($105)</option>
                        <option value="4">4 Persons ($140)</option>
                        <option value="5">5 Persons ($175)</option>
                        <option value="6">6 Persons ($210)</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="yogaDate">Preferred Session Date</label>
                      <input type="date" id="yogaDate" name="yogaDate">
                    </div>
                    <div class="opt-group">
                      <label for="yogaTime">Preferred Time of Day</label>
                      <select id="yogaTime" name="yogaTime">
                        <option value="Sunrise (7:00 AM)">Sunrise (7:00 AM)</option>
                        <option value="Morning (8:30 AM)">Morning (8:30 AM)</option>
                        <option value="Sunset (5:30 PM)">Sunset (5:30 PM)</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="yogaNotes">Optional Requests</label>
                      <input type="text" id="yogaNotes" name="yogaNotes" placeholder="e.g. Gentle flow, beach location preference, restorative stretch...">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Private Professional Photographer -->
              <div class="service-addon-featured service-addon-card" data-service-id="photographer" data-service-name="Private Professional Photographer" data-service-price="0" data-service-display="Enquiry Only – Price upon request">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_photographer" value="photographer">
                  <div class="service-addon-thumb">
                    <img src="assets/images/property-showcase-1.jpg" alt="Professional photography at villa" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Private Professional Photographer</h4>
                      <span class="service-enquiry-badge">Enquiry Only · Price upon request</span>
                    </div>
                    <p>Make your Aruba vacation memories last with a private professional photographer.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Enquiry Only · Full digital gallery delivered post-stay
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="photographerDate">Preferred Photography Date</label>
                      <input type="date" id="photographerDate" name="photographerDate">
                    </div>
                    <div class="opt-group">
                      <label for="photographerOccasion">Type of Occasion or Request</label>
                      <select id="photographerOccasion" name="photographerOccasion">
                        <option value="Couples / Sunset Beach Portrait">Couples / Sunset Beach Portrait</option>
                        <option value="Surprise Proposal / Engagement">Surprise Proposal / Engagement</option>
                        <option value="Anniversary / Milestone Celebration">Anniversary / Milestone Celebration</option>
                        <option value="Family Vacation Session">Family Vacation Session</option>
                        <option value="Lifestyle Villa & Pool Shoot">Lifestyle Villa &amp; Pool Shoot</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="photographerNotes">Optional Details &amp; Requests</label>
                      <textarea id="photographerNotes" name="photographerNotes" rows="2" placeholder="e.g. 1-hour sunset shoot at The Love Shack beach, preferred photography style..."></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- GROUP 4: Dining & Private Chef -->
            <div class="services-category-block">
              <div class="services-category-title">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                4. Dining &amp; Private Chef
              </div>

              <!-- Private Chef -->
              <div class="service-addon-featured service-addon-card" data-service-id="private-chef" data-service-name="Private Chef Experience" data-service-price="0" data-service-display="$85–$140 per person">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_private_chef" value="private-chef">
                  <div class="service-addon-thumb">
                    <img src="assets/images/architectural-design.jpg" alt="Private chef dining at villa" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Private Chef Experience</h4>
                      <span class="service-price-badge">$85–$140 per person</span>
                    </div>
                    <p>Enjoy an exceptional private dining experience prepared by a talented private chef. The experience includes appetizers, an entree and dessert. Breakfast and brunch are also available.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Chef cleans up after the meal · Advance booking recommended (chef books up quickly) · Final pricing &amp; availability confirmed by concierge
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label>Meal Type(s) Desired</label>
                      <div class="opt-checkboxes">
                        <label class="opt-checkbox-item"><input type="checkbox" id="chefMealDinner" checked> Dinner (3-Course)</label>
                        <label class="opt-checkbox-item"><input type="checkbox" id="chefMealBreakfast"> Breakfast</label>
                        <label class="opt-checkbox-item"><input type="checkbox" id="chefMealBrunch"> Island Brunch</label>
                      </div>
                    </div>
                    <div class="opt-group">
                      <label for="chefGuests">Number of Guests</label>
                      <select id="chefGuests" name="chefGuests">
                        <option value="2">2 Guests</option>
                        <option value="3">3 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="5">5 Guests</option>
                        <option value="6">6 Guests</option>
                      </select>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label>Wine Pairing Request</label>
                      <label class="opt-checkbox-item" style="margin-top: 4px;">
                        <input type="checkbox" id="chefWinePairing">
                        <span>If you would like wine pairing, please let us know in advance. The chef will bring the wine, with payment due upon arrival.</span>
                      </label>
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="chefDiet">Dietary Preferences &amp; Allergies</label>
                      <input type="text" id="chefDiet" name="chefDiet" placeholder="e.g. Seafood lovers, gluten-free, vegetarian, nut allergies...">
                    </div>
                    <div class="opt-group">
                      <label for="chefMenu">Special Menu Requests</label>
                      <input type="text" id="chefMenu" name="chefMenu" placeholder="e.g. Fresh Caribbean catch, prime steak, chocolate dessert...">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- GROUP 5: Adventure & Recreation -->
            <div class="services-category-block">
              <div class="services-category-title">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                5. Adventure &amp; Recreation
              </div>

              <!-- UTV Rentals -->
              <div class="service-addon-featured service-addon-card" data-service-id="utv-rental" data-service-name="UTV Rentals (Delivered to Villa)" data-service-price="0" data-service-display="$190 / $380 USD – 8 Hours">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_utv" value="utv-rental">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/arashi-beach.jpg" alt="UTV off-road vehicle rental" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>UTV Rentals (Delivered to Villa)</h4>
                      <span class="service-price-badge">$190 / $380 USD – 8 hours</span>
                    </div>
                    <p>Explore Aruba with a UTV delivered directly to the house for your convenience.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Two-seater UTV: $190 USD – 8 hours · Four-seater UTV: $380 USD – 8 hours · Direct villa delivery
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="utvModel">UTV Vehicle Model</label>
                      <select id="utvModel" name="utvModel">
                        <option value="Two-seater UTV ($190 – 8h)">Two-seater UTV ($190 USD – 8 hours)</option>
                        <option value="Four-seater UTV ($380 – 8h)">Four-seater UTV ($380 USD – 8 hours)</option>
                        <option value="Multiple UTVs">Multiple UTVs (Coordinate with Concierge)</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="utvDate">Requested Rental Date</label>
                      <input type="date" id="utvDate" name="utvDate">
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="utvNotes">Optional Rental Notes</label>
                      <input type="text" id="utvNotes" name="utvNotes" placeholder="e.g. Morning delivery time, recommended Arikok off-road route...">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Guided Hiking Tours & Excursions -->
              <div class="service-addon-featured service-addon-card" data-service-id="guided-hike" data-service-name="Guided Hiking Tours & Excursions" data-service-price="0" data-service-display="$50 per person">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_guided_hike" value="guided-hike">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/divi-tree-sunset.jpg" alt="Guided island hiking excursion" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Guided Hiking Tours &amp; Excursions</h4>
                      <span class="service-price-badge">$50 per person</span>
                    </div>
                    <p>Explore Aruba's hidden local locations and beautiful scenery with guided hiking tours and excursions hosted by Alexandra.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Hosted by Alexandra · Local hidden gems, coastal trails, and Arikok secret locations
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="guidedHikeParticipants">Number of Participants</label>
                      <select id="guidedHikeParticipants" name="guidedHikeParticipants">
                        <option value="1">1 Person ($50)</option>
                        <option value="2" selected>2 Persons ($100)</option>
                        <option value="3">3 Persons ($150)</option>
                        <option value="4">4 Persons ($200)</option>
                        <option value="5">5 Persons ($250)</option>
                        <option value="6">6 Persons ($300)</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="guidedHikeDate">Preferred Date</label>
                      <input type="date" id="guidedHikeDate" name="guidedHikeDate">
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="guidedHikeNotes">Optional Interests or Special Requests</label>
                      <input type="text" id="guidedHikeNotes" name="guidedHikeNotes" placeholder="e.g. Natural pool trek, coastal caves, sunrise viewpoint, easy or advanced trail...">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Private Fishing Charter -->
              <div class="service-addon-featured service-addon-card" data-service-id="fishing-charter" data-service-name="Private Fishing Charter" data-service-price="0" data-service-display="$600 USD (Up to 6 people)">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_fishing" value="fishing-charter">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/savaneta-sunset-boat.jpg" alt="Private fishing boat charter" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Private Fishing Charter</h4>
                      <span class="service-price-badge">$600 USD</span>
                    </div>
                    <p>Enjoy a private fishing charter for up to six people. Water and soft drinks are included.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Maximum capacity of six people · Water and soft drinks included
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="fishingParticipants">Number of Participants (Max 6)</label>
                      <select id="fishingParticipants" name="fishingParticipants">
                        <option value="2">2 Persons</option>
                        <option value="3">3 Persons</option>
                        <option value="4">4 Persons</option>
                        <option value="5">5 Persons</option>
                        <option value="6" selected>6 Persons (Maximum capacity)</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="fishingDate">Preferred Charter Date</label>
                      <input type="date" id="fishingDate" name="fishingDate">
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="fishingNotes">Optional Charter Requests</label>
                      <input type="text" id="fishingNotes" name="fishingNotes" placeholder="e.g. Deep sea game fish (Mahi-Mahi, Wahoo), calm coastal trolling, departure timing...">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Private Sunset Sailing Cruise (Existing Preserved) -->
              <div class="service-addon-featured service-addon-card" data-service-id="sailing-charter" data-service-name="Private Sunset Sailing Cruise" data-service-price="0" data-service-display="Enquiry Only – Price upon request">
                <div class="service-addon-main">
                  <input type="checkbox" class="service-checkbox" id="chk_sailing" value="sailing-charter">
                  <div class="service-addon-thumb">
                    <img src="assets/images/explore/aruba-sailing-rainbow.jpg" alt="Sunset sailing catamaran" loading="lazy">
                  </div>
                  <div class="service-addon-details">
                    <div class="service-addon-header">
                      <h4>Private Sunset Sailing Cruise</h4>
                      <span class="service-enquiry-badge">Enquiry Only · Price upon request</span>
                    </div>
                    <p>Charter a catamaran or sailboat for an unhurried sunset sail along the coast with open bar, bites, and reef snorkeling.</p>
                    <div class="service-addon-note">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                      Open bar &amp; light tapas · Snorkeling gear &amp; sunset views
                    </div>
                  </div>
                </div>
                <div class="service-options-panel">
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="sailingGuests">Number of Guests</label>
                      <select id="sailingGuests" name="sailingGuests">
                        <option value="2">2 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="6">6 Guests</option>
                      </select>
                    </div>
                    <div class="opt-group">
                      <label for="sailingDate">Preferred Date</label>
                      <input type="date" id="sailingDate" name="sailingDate">
                    </div>
                  </div>
                  <div class="opt-row">
                    <div class="opt-group">
                      <label for="sailingNotes">Optional Cruise Notes</label>
                      <input type="text" id="sailingNotes" name="sailingNotes" placeholder="e.g. Special celebration, sunset champagne toast, reef snorkeling...">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- GROUP 6: Complimentary Amenities -->
            <div class="services-category-block">
              <div class="services-category-title">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                6. Complimentary Amenities
              </div>

              <div class="complimentary-card-distinct">
                <div class="service-addon-header">
                  <h4>Complimentary Beach &amp; Water Sports Equipment</h4>
                  <span class="service-badge-complimentary">Complimentary with your stay</span>
                </div>
                <p>Enjoy complimentary access to our water sports equipment during your stay.</p>
                <div class="complimentary-items-grid">
                  <div class="complimentary-item-box">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    2 Stand-Up Paddleboards (SUP)
                  </div>
                  <div class="complimentary-item-box">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    2 Ocean Kayaks
                  </div>
                  <div class="complimentary-item-box">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Snorkeling Gear &amp; Masks
                  </div>
                </div>
                <p style="margin-top: 10px; font-size: 0.9rem; color: #557086; line-height: 1.5;">
                  There is amazing snorkeling right off our beach, and paddling out to the reef is a wonderful experience. We are directly across from Sea Glass Island, approximately a three-minute kayak trip away.
                </p>
              </div>
            </div>

            <p style="font-size: 0.88rem; color: var(--color-text-muted); text-align: center; margin: 18px 0 10px; font-style: italic;">
              All additional services are subject to availability and final confirmation by our concierge team. Outside vendor prices may change over time.
            </p>

            <!-- Bottom Navigation: BACK -> NEXT -->
            <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
              <button type="button" class="btn btn-outline" id="btnBackToStep2" style="flex: 1;">
                &larr; Back
              </button>
              <button type="button" class="btn btn-teal" id="btnGoToStep4" style="flex: 2;">
                Next &rarr;
              </button>
            </div>
          </div>

          <!-- STEP 4: Guest Details Form -->
          <div class="booking-step-pane" id="paneStep4">
            <span class="eyebrow">STEP 4 OF 5</span>
            <h2>Guest &amp; Contact Details</h2>
            <p>Please enter your contact information so our island concierge team can coordinate your reservation.</p>

            <!-- Arrival & Concierge Communication -->
            <div class="concierge-communication-card">
              <div class="concierge-comm-header">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                </svg>
                <div>
                  <h3>Your Arrival &amp; Concierge Communication</h3>
                  <p>First, please download WhatsApp to the cellphone you will be using and provide us with your phone number. Traci and Alexandra, our on-site co-host and house concierge, will use WhatsApp to communicate with you without incurring long-distance fees. They will assist you with making plans and anything else you may need assistance with.</p>
                </div>
              </div>
              <div class="concierge-contacts-grid">
                <div class="concierge-contact-pill">
                  <span class="contact-role">Property Host</span>
                  <strong>Traci’s Contact Number: (781) 964-8704</strong>
                </div>
                <div class="concierge-contact-pill">
                  <span class="contact-role">On-Site Co-Host &amp; House Concierge</span>
                  <strong>Alexandra</strong>
                </div>
              </div>
              <label class="whatsapp-confirm-label">
                <input type="checkbox" id="whatsappInstalled" name="whatsappInstalled">
                <span>I have WhatsApp installed on the cellphone I will be using during my stay.</span>
              </label>
            </div>

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

            <!-- Flight Information -->
            <div class="flight-info-card">
              <h3>Flight Information</h3>
              <p>Please provide your flight number so our team can track your arrival time.</p>
              <div class="form-group-row">
                <div class="form-group">
                  <label for="flightNumber">Flight Number</label>
                  <input type="text" id="flightNumber" name="flightNumber" placeholder="e.g. AA 1024, B6 1421, DL 892">
                </div>
                <div class="form-group">
                  <label for="airlineName">Airline Name (Optional)</label>
                  <input type="text" id="airlineName" name="airlineName" placeholder="e.g. American Airlines, JetBlue, Delta">
                </div>
              </div>
              <div class="form-group-row">
                <div class="form-group">
                  <label for="arrivalDate">Arrival Date (Optional)</label>
                  <input type="date" id="arrivalDate" name="arrivalDate">
                </div>
                <div class="form-group">
                  <label for="arrivalTime">Arrival Time (Optional)</label>
                  <input type="time" id="arrivalTime" name="arrivalTime">
                </div>
              </div>
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

            <!-- 1. Dates & Accommodation -->
            <div class="review-section-box">
              <h3>
                <span>1. Dates &amp; Accommodation</span>
                <button type="button" id="editStepDatesBtn" class="btn-text-link" style="font-size: 0.72rem;">Edit Dates</button>
              </h3>
              <div class="review-row">
                <span>Villa:</span>
                <strong>The Love Shack (Savaneta Beachfront, Aruba)</strong>
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
                <span>Nightly Accommodation Rate:</span>
                <strong id="revNightlyRate">$650.00 USD / night</strong>
              </div>
              <div class="review-row">
                <span>Accommodation Subtotal:</span>
                <strong id="revAccommodation">$0.00 USD</strong>
              </div>
              <div class="review-row">
                <span>Turnover &amp; Cleaning Fee:</span>
                <strong id="revCleaning">$180.00 USD</strong>
              </div>
            </div>

            <!-- 2. Selected Concierge Services -->
            <div class="review-section-box" id="revServicesBox">
              <h3>
                <span>2. Selected Concierge Services</span>
                <button type="button" id="editStepServicesBtn" class="btn-text-link" style="font-size: 0.72rem;">Edit Services</button>
              </h3>
              <div id="revServicesList">
                <div class="review-row" style="color: var(--color-text-muted); font-style: italic;">
                  No additional concierge services selected.
                </div>
              </div>
              <p style="font-size: 0.82rem; color: #557086; margin-top: 10px; border-top: 1px dashed #cbd5e1; padding-top: 8px;">
                Additional concierge services are requested separately and will be confirmed by our team. No additional service charges are collected through this request.
              </p>
            </div>

            <!-- 3. Guest & Contact Details -->
            <div class="review-section-box">
              <h3>
                <span>3. Guest &amp; Contact Details</span>
                <button type="button" id="editStepGuestBtn" class="btn-text-link" style="font-size: 0.72rem;">Edit Details</button>
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
              <div class="review-row" id="revWhatsAppStatusRow">
                <span>WhatsApp Installed:</span>
                <strong id="revWhatsAppStatus">Not specified</strong>
              </div>
              <div class="review-row" id="revFlightRow" style="display: none;">
                <span>Flight Details:</span>
                <strong id="revFlightInfo">—</strong>
              </div>
              <div class="review-row" id="revNotesRow" style="display: none;">
                <span>Special Requests:</span>
                <span id="revSpecialRequests" style="color: var(--color-text-muted); font-style: italic;">—</span>
              </div>
            </div>

            <!-- 4. Estimated Accommodation Total -->
            <div class="review-section-box" style="background: #fafafa; border-color: var(--color-secondary);">
              <h3><span>4. Estimated Accommodation Total</span></h3>
              <div class="review-total-row" style="border: none; padding: 0; font-size: 1.35rem;">
                <span>Total Accommodation Due:</span>
                <span id="revGrandTotal" style="color: var(--color-primary);">$0.00 USD</span>
              </div>
              <p style="font-size: 0.82rem; color: var(--color-text-muted); margin-top: 6px;">Includes accommodation subtotal and $180 cleaning fee. Does not include unconfirmed concierge add-ons.</p>
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

            <div id="bookingSubmitActions" style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
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
