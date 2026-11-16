<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Production Homepage
 */

declare(strict_types=1);

$activePage = 'home';
$pageTitle = 'The Love Shack Aruba | Luxury Beachfront Sanctuary · Savaneta';
$pageDescription = 'An intimate luxury beachfront hideaway in Savaneta, Aruba. Private swimming pool, direct Caribbean ocean access, and 3 private en-suite suites.';

require_once __DIR__ . '/includes/header.php';

$property = get_property_details();
$rooms = get_rooms((int)($property['id'] ?? 1));
$gallery = get_gallery_images((int)($property['id'] ?? 1), true);
$amenitiesGrouped = get_amenities_grouped((int)($property['id'] ?? 1));
$reviews = get_reviews((int)($property['id'] ?? 1), 12);
?>

  <main>
    <!-- ====================================================================
         1. HERO SECTION WITH PROPERTY IMAGERY & PRIMARY BOOKING CTA
         ==================================================================== -->
    <section class="hero" aria-label="Welcome to The Love Shack Aruba">
      <div class="hero-bg" role="img" aria-label="Caribbean beachfront and ocean hero photograph" style="background-image: url('<?= htmlspecialchars($property['beach_hero_image'] ?? 'assets/images/beach-hero.jpg') ?>');"></div>
      <div class="hero-overlay"></div>
      
      <div class="hero-content">
        <div class="hero-tag">
          <span>SAVANETA · ARUBA</span>
        </div>

        <h1><?= htmlspecialchars($property['hero_heading'] ?? 'Your beachfront escape in Aruba.') ?></h1>
        
        <p class="hero-description">
          <?= htmlspecialchars($property['hero_description'] ?? 'An intimate beachfront hideaway where barefoot island living meets understated elegance. Wake to gentle Caribbean waves and step directly from your private pool terrace onto secluded Aruban sands.') ?>
        </p>

        <div class="hero-actions">
          <a class="btn btn-teal" href="booking.php">
            Book Your Stay
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </a>
          <a class="btn btn-glass" href="property.php#accommodations">
            Explore Property
          </a>
          <a class="btn btn-glass" href="contact.php">
            Inquire With Host
          </a>
        </div>

        <!-- Central Availability Search Bar -->
        <div class="hero-search-wrapper" aria-label="Search Availability">
          <form class="hero-search-bar" id="heroSearchForm" action="booking.php" method="GET">
            <div class="search-field">
              <div class="search-field-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </div>
              <div class="search-field-body">
                <label for="heroCheckin">Check-In</label>
                <input type="date" id="heroCheckin" name="checkin" required aria-label="Check-in date">
              </div>
            </div>

            <div class="search-field">
              <div class="search-field-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </div>
              <div class="search-field-body">
                <label for="heroCheckout">Check-Out</label>
                <input type="date" id="heroCheckout" name="checkout" required aria-label="Check-out date">
              </div>
            </div>

            <div class="search-field">
              <div class="search-field-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
              </div>
              <div class="search-field-body">
                <label for="heroGuests">Guests</label>
                <select id="heroGuests" name="guests" aria-label="Number of guests">
                  <option value="1">1 Guest</option>
                  <option value="2" selected>2 Guests (Couple)</option>
                  <option value="3">3 Guests</option>
                  <option value="4">4 Guests (Family)</option>
                  <option value="5">5 Guests</option>
                  <option value="6">6 Guests (Max)</option>
                </select>
              </div>
            </div>

            <button type="submit" class="search-btn" aria-label="Check Availability">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <span>Check Dates</span>
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         2. PROPERTY INTRODUCTION / OVERVIEW (SAVANETA · ARUBA)
         ==================================================================== -->
    <section class="intro-section wrap" id="overview" aria-label="About The Love Shack">
      <div class="intro-grid">
        <div class="intro-visual">
          <div class="intro-image-frame">
            <img src="assets/images/temporary-beach.jpg" alt="The Love Shack beachfront tropical coastline in Savaneta, Aruba">
          </div>
          <div class="intro-badge">
            <strong>SAVANETA</strong>
            <span>Peaceful Coastline</span>
          </div>
        </div>

        <div class="intro-content">
          <span class="eyebrow"><?= htmlspecialchars($property['intro_badge'] ?? 'SAVANETA · ARUBA') ?></span>
          <h2><?= htmlspecialchars($property['intro_title'] ?? "Barefoot luxury on Aruba's peaceful southern coast.") ?></h2>
          
          <p>
            <?= nl2br(htmlspecialchars($property['intro_content'] ?? 'Tucked along the peaceful coastline of Savaneta, The Love Shack redefines island ease.')) ?>
          </p>

          <ul class="intro-checklist">
            <li>
              <span class="benefit-dot"></span>
              <span>100% private freshwater swimming pool with dedicated double sun lounger terrace</span>
            </li>
            <li>
              <span class="benefit-dot"></span>
              <span>Immediate beach access steps from your covered outdoor lanai</span>
            </li>
            <li>
              <span class="benefit-dot"></span>
              <span>3 private en-suite suites accommodating up to 6 discerning guests</span>
            </li>
            <li>
              <span class="benefit-dot"></span>
              <span>Walking distance to world-renowned waterfront dining at Zeerovers</span>
            </li>
          </ul>

          <a class="btn-text-link" href="property.php#accommodations">
            Explore the property spaces & floor plans
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================================
         3. A GLIMPSE OF PARADISE – PROPERTY SHOWCASE GALLERY
         ==================================================================== -->
    <section class="gallery-section wrap" id="gallery" aria-label="Villa Gallery Preview">
      <div class="gallery-header-row">
        <div>
          <span class="eyebrow">PROPERTY SHOWCASE</span>
          <h2>Intimate moments, <em>unhurried luxury.</em></h2>
        </div>
        <a class="btn btn-outline" href="property.php#gallery">
          Explore All Property Views ↗
        </a>
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
         4. ACCOMMODATION HIGHLIGHTS
         ==================================================================== -->
    <section class="highlights-section wrap" id="highlights" aria-label="Accommodation Highlights">
      <!-- 5-Metric Quick Bar -->
      <div class="amenity-strip-container" style="margin-bottom: 50px;">
        <div class="strip-item">
          <div class="strip-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"></path></svg>
          </div>
          <div class="strip-text">
            <strong><?= (int)($property['bedrooms'] ?? 3) ?> Bedrooms</strong>
            <span>All En-Suite Baths</span>
          </div>
        </div>

        <div class="strip-item">
          <div class="strip-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6h6M9 10h6M5 14h14a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2z"></path></svg>
          </div>
          <div class="strip-text">
            <strong><?= (int)($property['bathrooms'] ?? 3) ?> Bathrooms</strong>
            <span>Private Rain Showers</span>
          </div>
        </div>

        <div class="strip-item">
          <div class="strip-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <div class="strip-text">
            <strong>Sleeps <?= (int)($property['max_guests'] ?? 6) ?> Guests</strong>
            <span>Generous Comfort</span>
          </div>
        </div>

        <div class="strip-item">
          <div class="strip-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M12 3a9 9 0 0 0 0 18v-9h9"></path></svg>
          </div>
          <div class="strip-text">
            <strong>Private Pool</strong>
            <span>Freshwater & Lanai</span>
          </div>
        </div>

        <div class="strip-item">
          <div class="strip-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h20M2 17h20M2 7h20"></path></svg>
          </div>
          <div class="strip-text">
            <strong>Direct Beach</strong>
            <span>Steps to Calm Water</span>
          </div>
        </div>
      </div>

      <!-- Experience Story Cards -->
      <div class="section-header center">
        <span class="eyebrow center">THE LOVE SHACK EXPERIENCE</span>
        <h2>Designed for <em>slow island days.</em></h2>
        <p>Every corner is crafted for tranquility, shared Caribbean meals, and restorative moments by the water.</p>
      </div>

      <div class="experience-grid">
        <article class="experience-card">
          <div class="experience-card-inner">
            <div class="experience-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
              </svg>
            </div>
            <h3>Slow Island Mornings</h3>
            <p>Start your day with artisan coffee on the sun-drenched lanai, listening to calm morning waters and birdsong.</p>
          </div>
        </article>

        <article class="experience-card">
          <div class="experience-card-inner">
            <div class="experience-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 12h20"></path>
                <path d="M2 17c3 0 3-2 6-2s3 2 6 2 3-2 6-2 3 2 4 2"></path>
                <circle cx="12" cy="7" r="3"></circle>
              </svg>
            </div>
            <h3>Poolside Seclusion</h3>
            <p>Take refreshing afternoon dips in your private freshwater swimming pool, secluded by lush tropical gardens and palms.</p>
          </div>
        </article>

        <article class="experience-card">
          <div class="experience-card-inner">
            <div class="experience-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </div>
            <h3>Sunset Living Together</h3>
            <p>Watch Aruba’s famous golden sunsets paint the Caribbean horizon from the beachfront terrace with loved ones.</p>
          </div>
        </article>
      </div>
    </section>

    <!-- ====================================================================
         5. ROOMS AND SUITES OVERVIEW
         ==================================================================== -->
    <section class="rooms-preview-section wrap" id="rooms" aria-label="Rooms and Suites Overview">
      <div class="section-header center">
        <span class="eyebrow center">ROOMS & SUITES OVERVIEW</span>
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

              <div style="margin-top: 18px;">
                <a class="btn-text-link" href="property.php#accommodations">
                  View suite details & photos
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ====================================================================
         6. AMENITIES AND FACILITIES
         ==================================================================== -->
    <section class="amenities-preview-section wrap" id="amenities" aria-label="Curated Amenities">
      <div class="section-header center">
        <span class="eyebrow center">CONFIRMED PROPERTY AMENITIES</span>
        <h2>What this place <em>offers.</em></h2>
        <p>Curated comforts and facilities verified for your private Savaneta getaway.</p>
      </div>

      <div class="amenities-popular-grid">
        <?php 
        $popular = $amenitiesGrouped['Popular'] ?? [];
        foreach ($popular as $am): 
        ?>
          <div class="amenity-popular-card">
            <div class="amenity-popular-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
              </svg>
            </div>
            <div class="amenity-popular-text">
              <h4><?= htmlspecialchars($am['name']) ?></h4>
              <p><?= htmlspecialchars($am['description'] ?? '') ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="amenities-action-wrap" style="text-align: center; margin-top: 36px;">
        <button class="btn btn-outline" id="openAmenitiesModalBtn" type="button" aria-haspopup="dialog">
          Show All Amenities (35+ Facilities)
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </button>
      </div>
    </section>

    <!-- ====================================================================
         7. LOCATION AND NEARBY ATTRACTIONS
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
         9. HOUSE RULES AND IMPORTANT PROPERTY INFORMATION
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
         10. FINAL BOOKING CALL TO ACTION
         ==================================================================== -->
    <section class="booking-cta-section wrap" aria-label="Reserve The Love Shack">
      <div class="booking-cta-bg"></div>
      <div class="booking-cta-content">
        <span class="eyebrow light center">DIRECT BOOKING INQUIRY</span>
        <h2>Your private beachfront sanctuary <em>awaits.</em></h2>
        <p>Book directly with the on-island concierge team for verified calendar availability, bespoke itinerary planning, and personalized Aruban hospitality.</p>
        
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

  <!-- ====================================================================
       11. FULL 7-CATEGORY AMENITIES MODAL
       ==================================================================== -->
  <div class="amenities-modal" id="amenitiesModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="amenitiesModalTitle">
    <div class="amenities-modal-backdrop" id="amenitiesModalBackdrop"></div>
    <div class="amenities-modal-dialog">
      <div class="amenities-modal-header">
        <h3 id="amenitiesModalTitle">All Confirmed Amenities</h3>
        <button class="amenities-modal-close" id="closeAmenitiesModalBtn" type="button" aria-label="Close amenities dialog">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <div class="amenities-modal-body">
        <div class="amenities-categories">
          <?php foreach ($amenitiesGrouped as $categoryName => $catItems): ?>
            <?php if (!empty($catItems)): ?>
              <div class="amenity-category-block">
                <h4><?= htmlspecialchars($categoryName) ?></h4>
                <div class="amenity-modal-items">
                  <?php foreach ($catItems as $item): ?>
                    <div class="amenity-modal-item">
                      <div class="item-icon" aria-hidden="true">
                        <span class="amenity-dot"></span>
                      </div>
                      <div>
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
      </div>
    </div>
  </div>

<?php 
// 12. FOOTER WITH CONTACT DETAILS AND USEFUL LINKS
require_once __DIR__ . '/includes/footer.php'; 
?>
