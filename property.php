<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Production Property Details & Architectural Overview
 */

declare(strict_types=1);

$activePage = 'property';
$pageTitle = 'The Property & Rooms | The Love Shack — Savaneta, Aruba';
$pageDescription = 'Discover the spaces, rooms, private freshwater swimming pool, and luxury amenities at The Love Shack in Savaneta, Aruba. 3 en-suite bedrooms, sleeps 6 guests.';

require_once __DIR__ . '/includes/header.php';

$property = get_property_details();
$rooms = get_rooms((int)($property['id'] ?? 1));
$gallery = get_gallery_images((int)($property['id'] ?? 1));
$amenitiesGrouped = get_amenities_grouped((int)($property['id'] ?? 1));
?>

  <main>
    <!-- ====================================================================
         1. PROPERTY OVERVIEW: HERO BANNER & ARCHITECTURAL NARRATIVE
         ==================================================================== -->
    <section class="page-banner" id="overview" aria-label="The Property Details">
      <div class="page-banner-bg" role="img" aria-label="The Love Shack Villa Background"></div>
      <div class="page-banner-overlay"></div>
      <div class="page-banner-content">
        <span class="eyebrow light center">ARCHITECTURAL SPOTLIGHT</span>
        <h1>Secluded beachfront <em>spaces.</em></h1>
        <p>
          Discover the thoughtfully curated spaces, secluded freshwater pool, and oceanfront comforts that make The Love Shack Aruba's premier private hideaway.
        </p>
      </div>
    </section>

    <!-- In-Page Section Quick Jump Navigation -->
    <nav class="prop-subnav-bar wrap" aria-label="Property sections navigation">
      <div class="prop-subnav-container">
        <a href="#overview" class="subnav-pill">Overview</a>
        <a href="#gallery" class="subnav-pill">Full Gallery</a>
        <a href="#rooms" class="subnav-pill">Bedrooms & Suites</a>
        <a href="#bathrooms" class="subnav-pill">Bathrooms & Facilities</a>
        <a href="#amenities" class="subnav-pill">Amenities</a>
        <a href="#highlights" class="subnav-pill">Highlights</a>
        <a href="#location" class="subnav-pill">Location</a>
        <a href="#rules" class="subnav-pill">House Rules</a>
      </div>
    </nav>

    <!-- Specifications Quick Bar -->
    <section class="prop-specs-bar-wrap wrap" aria-label="Villa Key Metrics">
      <div class="specs-bar">
        <div class="specs-item">
          <span class="specs-label">BEDROOMS</span>
          <span class="specs-val"><?= (int)($property['bedrooms'] ?? 3) ?> En-Suite Suites</span>
        </div>
        <div class="specs-item">
          <span class="specs-label">BATHROOMS</span>
          <span class="specs-val"><?= (int)($property['bathrooms'] ?? 3) ?> Full Baths</span>
        </div>
        <div class="specs-item">
          <span class="specs-label">CAPACITY</span>
          <span class="specs-val">Sleeps <?= (int)($property['max_guests'] ?? 6) ?> Guests</span>
        </div>
        <div class="specs-item">
          <span class="specs-label">FRESHWATER POOL</span>
          <span class="specs-val">100% Private Pool</span>
        </div>
        <div class="specs-item">
          <span class="specs-label">BEACHFRONT</span>
          <span class="specs-val">Direct Ocean Sand</span>
        </div>
      </div>
    </section>

    <!-- Architectural Narrative & Story -->
    <section class="story-section wrap" aria-label="Villa Design Story">
      <div class="story-grid">
        <div class="story-content">
          <span class="eyebrow">DESIGN PHILOSOPHY</span>
          <h2>Where open-air ease meets <em>timeless refinement.</em></h2>
          <p>
            <?= nl2br(htmlspecialchars($property['intro_content'] ?? 'The Love Shack was created as an unpretentious luxury retreat where indoors and outdoors blend seamlessly.')) ?>
          </p>
          
          <div class="story-highlights">
            <div class="story-highlight-item">
              <strong>Private Pool & Ocean Sun Deck</strong>
              <p>Step directly from your private bedroom terrace onto cool Aruban tiles and into your secluded swimming pool.</p>
            </div>
            <div class="story-highlight-item">
              <strong>Authentic Savaneta Setting</strong>
              <p>Unspoiled southern coastline away from high-rise crowds, famous for tranquil waters and seaside seafood.</p>
            </div>
          </div>
        </div>

        <div class="story-media">
          <div class="story-image-wrap">
            <img src="<?= htmlspecialchars($property['hero_image'] ?? 'assets/images/temporary-hero.jpg') ?>" alt="The Love Shack private swimming pool and garden patio">
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         2. FULL IMAGE GALLERY
         ==================================================================== -->
    <section class="gallery-section wrap" id="gallery" aria-label="Photo Gallery">
      <div class="gallery-header-row">
        <div>
          <span class="eyebrow">PROPERTY SHOWCASE</span>
          <h2>A glimpse of <em>paradise.</em></h2>
        </div>
        <p style="max-width: 480px; color: var(--color-text-muted);">
          Explore the secluded beachfront veranda, sun-drenched private pool terrace, and peaceful Caribbean shoreline. Click any photograph to inspect in full detail.
        </p>
      </div>

      <div class="gallery-grid">
        <?php foreach ($gallery as $item): ?>
          <div class="gallery-item" data-caption="<?= htmlspecialchars($item['caption']) ?>">
            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
            <div class="gallery-overlay">
              <span class="gallery-tag"><?= htmlspecialchars($item['category'] ?? 'Villa View') ?></span>
              <p class="gallery-caption"><?= htmlspecialchars($item['title']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ====================================================================
         3. BEDROOMS AND ACCOMMODATION DETAILS
         ==================================================================== -->
    <section class="rooms-section wrap" id="rooms" aria-label="Suites and Bedrooms">
      <div class="section-header center">
        <span class="eyebrow center">PRIVATE ACCOMMODATIONS</span>
        <h2>Three private <em>sanctuary suites.</em></h2>
        <p>Each suite is designed with its own private en-suite bathroom, quiet air conditioning, and luxury linens.</p>
      </div>

      <div class="rooms-grid">
        <?php foreach ($rooms as $room): ?>
          <div class="room-card">
            <div class="room-thumb">
              <img src="<?= htmlspecialchars($room['image_url'] ?? 'assets/images/temporary-hero.jpg') ?>" alt="<?= htmlspecialchars($room['name']) ?>">
              <span class="room-type-tag"><?= htmlspecialchars($room['suite_type']) ?></span>
            </div>
            <div class="room-details">
              <h3><?= htmlspecialchars($room['name']) ?></h3>
              <p><?= htmlspecialchars($room['description']) ?></p>
              
              <ul class="room-specs-list">
                <li>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"></path></svg>
                  <span><?= htmlspecialchars($room['bed_configuration']) ?></span>
                </li>
                <li>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6h6M9 10h6M5 14h14a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2z"></path></svg>
                  <span><?= htmlspecialchars($room['bathroom_details']) ?></span>
                </li>
              </ul>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ====================================================================
         4. BATHROOMS AND FACILITIES
         ==================================================================== -->
    <section class="bathrooms-section wrap" id="bathrooms" aria-label="Bathrooms and Facilities">
      <div class="section-header center">
        <span class="eyebrow center">EN-SUITE SANCTUARIES</span>
        <h2>Bathrooms & <em>personal care.</em></h2>
        <p>Every bedroom enjoys its own dedicated private full bathroom, plus a refreshing outdoor poolside rinse shower.</p>
      </div>

      <div class="bathrooms-grid">
        <div class="bathroom-card">
          <div class="bath-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6h6M9 10h6M5 14h14a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2z"></path></svg>
          </div>
          <h3>Master En-Suite Bathroom</h3>
          <p>Features a spacious walk-in rainfall shower, dual vanity mirrors, organic Caribbean bath amenities, and plush oversized towels.</p>
          <ul class="bath-feature-list">
            <li>Walk-in rainfall shower</li>
            <li>Double vanity sinks</li>
            <li>Organic hair & body care</li>
            <li>Plush bath sheets & robes</li>
          </ul>
        </div>

        <div class="bathroom-card">
          <div class="bath-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6h6M9 10h6M5 14h14a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2z"></path></svg>
          </div>
          <h3>Guest En-Suite Bathrooms (2)</h3>
          <p>Two private guest bathrooms, each adjacent to its corresponding suite with refreshing rainfall showerheads and warm water.</p>
          <ul class="bath-feature-list">
            <li>Private en-suite access</li>
            <li>Modern rain shower</li>
            <li>Hair dryers & vanity lighting</li>
            <li>Fresh cotton towels daily</li>
          </ul>
        </div>

        <div class="bathroom-card">
          <div class="bath-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M12 3a9 9 0 0 0 0 18v-9h9"></path></svg>
          </div>
          <h3>Outdoor Poolside Rinse Shower</h3>
          <p>Step directly from the beach onto the pool terrace and rinse off under an open-air shower enveloped by Aruban palms.</p>
          <ul class="bath-feature-list">
            <li>Open-air tropical setting</li>
            <li>Post-ocean freshwater rinse</li>
            <li>Direct pool deck access</li>
            <li>Natural coral stone flooring</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         5. AMENITIES
         ==================================================================== -->
    <section class="amenities-preview-section wrap" id="amenities" aria-label="Amenities">
      <div class="section-header center">
        <span class="eyebrow center">VERIFIED COMFORTS</span>
        <h2>Curated villa <em>amenities.</em></h2>
        <p>Everything you need for an unhurried luxury stay in Savaneta, Aruba.</p>
      </div>

      <div class="amenities-categories-full">
        <?php foreach ($amenitiesGrouped as $categoryName => $catItems): ?>
          <?php if (!empty($catItems)): ?>
            <div class="amenity-cat-full-block">
              <h3 class="amenity-cat-title"><?= htmlspecialchars($categoryName) ?></h3>
              <div class="amenities-cat-grid">
                <?php foreach ($catItems as $item): ?>
                  <div class="amenity-item-row">
                    <div class="amenity-icon-bullet" aria-hidden="true">
                      <span class="amenity-dot"></span>
                    </div>
                    <div class="amenity-text-block">
                      <strong><?= htmlspecialchars($item['name']) ?></strong>
                      <?php if (!empty($item['description'])): ?>
                        <p><?= htmlspecialchars($item['description']) ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ====================================================================
         6. PROPERTY HIGHLIGHTS
         ==================================================================== -->
    <section class="highlights-section wrap" id="highlights" aria-label="Property Highlights">
      <div class="section-header center">
        <span class="eyebrow center">SIGNATURE EXPERIENCES</span>
        <h2>Key property <em>highlights.</em></h2>
        <p>Distinctive features that set The Love Shack apart as Savaneta's crown jewel.</p>
      </div>

      <div class="experience-grid">
        <div class="experience-card">
          <div class="experience-card-inner">
            <span class="experience-num">01</span>
            <h3>100% Private Pool</h3>
            <p>Your freshwater swimming pool is reserved exclusively for your party, completely sheltered from external island traffic.</p>
          </div>
        </div>

        <div class="experience-card">
          <div class="experience-card-inner">
            <span class="experience-num">02</span>
            <h3>Direct Beach Sand</h3>
            <p>Step directly from your covered outdoor lanai onto soft Aruban sand and into calm Caribbean turquoise waters.</p>
          </div>
        </div>

        <div class="experience-card">
          <div class="experience-card-inner">
            <span class="experience-num">03</span>
            <h3>Covered Dining Lanai</h3>
            <p>Alfresco oceanfront dining table seating up to 8 guests with outdoor ceiling fans and sunset views every evening.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         7. LOCATION INFORMATION
         ==================================================================== -->
    <section class="location-section wrap" id="location" aria-label="Neighborhood and Location">
      <div class="location-grid">
        <div class="location-info">
          <span class="eyebrow">LOCATION & SURROUNDINGS</span>
          <h2>The authentic soul of <em>Aruba.</em></h2>
          <p>
            Savaneta is Aruba's oldest town and historic former capital. Renowned for calm lagoons, peaceful mangroves, and fresh daily catch landed right at the dock.
          </p>

          <div class="location-points">
            <div class="loc-point">
              <span class="loc-time">02 MIN</span>
              <div>
                <strong>Zeerovers Seafood Restaurant</strong>
                <p>Casual waterfront icon where fresh catch of the day is weighed and fried to order over the Caribbean.</p>
              </div>
            </div>

            <div class="loc-point">
              <span class="loc-time">04 MIN</span>
              <div>
                <strong>The Flying Fishbone</strong>
                <p>Barefoot fine-dining tables set directly in the gentle surf for unforgettable sunset dinners.</p>
              </div>
            </div>

            <div class="loc-point">
              <span class="loc-time">08 MIN</span>
              <div>
                <strong>Mangel Halto Beach & Reef</strong>
                <p>World-class snorkeling, crystal turquoise channels, and calm paddleboarding through mangrove canals.</p>
              </div>
            </div>

            <div class="loc-point">
              <span class="loc-time">15 MIN</span>
              <div>
                <strong>Queen Beatrix International Airport (AUA)</strong>
                <p>Convenient, scenic highway drive directly from airport arrival to your private beachfront sanctuary.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="location-visual">
          <div class="map-visual-card">
            <div class="map-header">
              <div class="map-pin-pulse"></div>
              <div>
                <strong><?= htmlspecialchars($property['name'] ?? 'The Love Shack') ?></strong>
                <span><?= htmlspecialchars($settings['property_location'] ?? 'Savaneta Beachfront, Aruba') ?></span>
              </div>
            </div>
            <div class="map-inner-preview">
              <img src="assets/images/temporary-hero.jpg" alt="The Love Shack aerial view">
              <div class="map-overlay-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>Direct Beachfront · 12.4419° N, 69.9328° W</span>
              </div>
            </div>
            <div class="map-footer">
              <a class="btn-text-link" href="contact.php">
                Request private airport transfer or driver details
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         8. HOUSE RULES AND POLICIES
         ==================================================================== -->
    <section class="rules-section wrap" id="rules" style="margin-bottom: 90px;" aria-label="House Policies">
      <div class="rules-card">
        <div>
          <span class="eyebrow">HOUSE POLICIES</span>
          <h3>Staying at The Love Shack</h3>
          <p>We invite you to relax and treat our sanctuary as your private island home. Please review our basic stay standards.</p>
        </div>
        <div class="rules-list">
          <div class="rule-box">
            <span class="rule-num">01</span>
            <div class="rule-text">
              <strong>Check-in & Check-out</strong>
              <p>Check-in from <?= htmlspecialchars($settings['checkin_time'] ?? '15:00') ?>; Check-out by <?= htmlspecialchars($settings['checkout_time'] ?? '11:00') ?>. Digital keyless lock.</p>
            </div>
          </div>
          <div class="rule-box">
            <span class="rule-num">02</span>
            <div class="rule-text">
              <strong>Quiet Island Hours</strong>
              <p>Savaneta is a peaceful residential fishing hamlet. Respectful quiet hours commence at 10:00 PM.</p>
            </div>
          </div>
          <div class="rule-box">
            <span class="rule-num">03</span>
            <div class="rule-text">
              <strong>Guest Capacity</strong>
              <p>Maximum <?= (int)($property['max_guests'] ?? 6) ?> registered guests allowed overnight. No unauthorized commercial events.</p>
            </div>
          </div>
          <div class="rule-box">
            <span class="rule-num">04</span>
            <div class="rule-text">
              <strong>Non-Smoking Sanctuary</strong>
              <p>Smoking is strictly prohibited inside all indoor suites and living quarters.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         9. BOOKING CALL TO ACTION
         ==================================================================== -->
    <section class="booking-cta-section wrap" aria-label="Book Direct">
      <div class="booking-cta-bg"></div>
      <div class="booking-cta-content">
        <span class="eyebrow light center">DIRECT CONCIERGE ADVANTAGE</span>
        <h2>Your private Aruban beach is <em>calling.</em></h2>
        <p>Check availability for your dates and reserve direct with no booking middleman commissions.</p>
        <div class="booking-cta-actions">
          <a class="btn btn-white" href="booking.php">
            Check Availability
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </a>
          <a class="btn btn-glass" href="contact.php">
            Message Concierge
          </a>
        </div>
      </div>
    </section>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
