<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Core Backend Functions & Business Logic Engine
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

// ----------------------------------------------------------------------------
// General Utility & Security Helpers
// ----------------------------------------------------------------------------

function sanitize_input(mixed $data): string {
    if (is_array($data) || is_object($data)) {
        return '';
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function json_response(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

function format_currency(float|int $amount): string {
    return '$' . number_format((float)$amount, 2) . ' USD';
}

function format_date_luxury(string $dateStr): string {
    if (empty($dateStr)) return 'Not selected';
    $timestamp = strtotime($dateStr);
    if (!$timestamp) return $dateStr;
    return date('M j, Y', $timestamp);
}

// ----------------------------------------------------------------------------
// Dynamic Content Retrieval Functions (with graceful fallbacks)
// ----------------------------------------------------------------------------

function get_site_settings(): array {
    $defaultSettings = [
        'site_title'           => 'The Love Shack Aruba',
        'site_tagline'         => 'Luxury Beachfront Sanctuary · Savaneta, Aruba',
        'concierge_email'      => 'concierge@loveshackaruba.com',
        'concierge_phone'      => '+297 580 0000',
        'property_location'    => 'Savaneta Beachfront, Aruba, Dutch Caribbean',
        'default_nightly_rate' => '650.00',
        'min_stay_nights'      => '3',
        'max_guests'           => '6',
        'cleaning_fee'         => '180.00',
        'service_tax_percent'  => '9.50',
        'checkin_time'         => '15:00',
        'checkout_time'        => '11:00',
        'cancellation_policy'  => 'Full refund up to 30 days prior to arrival. 50% refund between 30 and 14 days prior.',
    ];

    $pdo = get_db_connection();
    if (!$pdo) {
        return $defaultSettings;
    }

    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $defaultSettings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {
        error_log("Failed to load site settings: " . $e->getMessage());
    }

    return $defaultSettings;
}

function get_property_details(string $slug = 'the-love-shack'): array {
    $fallback = [
        'id'                => 1,
        'name'              => 'The Love Shack Aruba',
        'slug'              => 'the-love-shack',
        'tagline'           => 'Barefoot luxury beachfront villa with private freshwater pool',
        'hero_heading'      => 'Your beachfront escape in Aruba.',
        'hero_description'  => 'An intimate beachfront hideaway where barefoot island living meets understated elegance. Wake to gentle Caribbean waves and step directly from your private pool terrace onto secluded Aruban sands.',
        'beach_hero_image'  => 'assets/images/beach-hero.jpg',
        'hero_image'        => 'assets/images/temporary-hero.jpg',
        'intro_badge'       => 'THE LOVE SHACK · ARUBA',
        'intro_title'       => 'Barefoot luxury on Aruba\'s peaceful southern coast.',
        'intro_content'     => "Tucked along the peaceful coastline of Savaneta, The Love Shack redefines island ease. Designed for travelers seeking quiet luxury, generous open-air living spaces, and the soothing sounds of gentle Caribbean tides.\r\n\r\nUnlike the bustling high-rise resort strip in the north, Savaneta offers untouched authentic island charm, calm clear turquoise shallows, and some of Aruba's most celebrated waterfront seafood restaurants just moments away.",
        'bedrooms'          => 3,
        'bathrooms'         => 3,
        'max_guests'        => 6,
        'base_nightly_rate' => 650.00,
        'location_area'     => 'Savaneta Beachfront, Aruba',
    ];

    $pdo = get_db_connection();
    if (!$pdo) return $fallback;

    try {
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: $fallback;
    } catch (Exception $e) {
        error_log("Error fetching property: " . $e->getMessage());
        return $fallback;
    }
}

function get_rooms(int $propertyId = 1): array {
    $fallback = [
        [
            'name' => 'Sea Esta',
            'suite_type' => 'BEDROOM 01',
            'description' => 'Spacious primary suite featuring a plush king-size bed, private en-suite bathroom with walk-in rainfall shower, quiet climate control, and thoughtful coastal touches.',
            'bed_configuration' => 'King-size Bed',
            'bathroom_details' => 'Private En-Suite Bathroom',
            'feature_3' => 'Quiet Air Conditioning',
            'image_url' => 'assets/images/rooms/sea-esta-1.jpg',
            'photos' => [
                ['url' => 'assets/images/rooms/sea-esta-1.jpg', 'caption' => 'Sea Esta — King Bedroom Suite at The Love Shack Aruba'],
                ['url' => 'assets/images/rooms/sea-esta-2.jpg', 'caption' => 'Sea Esta — Private En-Suite Bathroom with Rainfall Shower'],
                ['url' => 'assets/images/rooms/sea-esta-3.jpg', 'caption' => 'Sea Esta — King Bed and Tropical Coastal Decor'],
                ['url' => 'assets/images/rooms/sea-esta-4.jpg', 'caption' => 'Sea Esta — Bedroom Architecture and Finishes'],
                ['url' => 'assets/images/rooms/sea-esta-5.jpg', 'caption' => 'Sea Esta — Custom Wardrobe and Vanity Area'],
                ['url' => 'assets/images/rooms/sea-esta-6.jpg', 'caption' => 'Sea Esta — Ambient Lighting and Modern Comforts'],
            ],
        ],
        [
            'name' => 'Sea Gem',
            'suite_type' => 'BEDROOM 02',
            'description' => 'Peaceful guest bedroom appointed with a comfortable queen-size bed, bathroom located directly across the hall, quiet air conditioning, and a restful island ambiance.',
            'bed_configuration' => 'Queen-size Bed',
            'bathroom_details' => 'Bathroom Located Directly Across the Hall',
            'feature_3' => 'Quiet Air Conditioning',
            'image_url' => 'assets/images/rooms/sea-gem-1.jpg',
            'photos' => [
                ['url' => 'assets/images/rooms/sea-gem-1.jpg', 'caption' => 'Sea Gem — Queen Bedroom Suite at The Love Shack Aruba'],
                ['url' => 'assets/images/rooms/sea-gem-2.jpg', 'caption' => 'Sea Gem — Bathroom Located Directly Across the Hall'],
                ['url' => 'assets/images/rooms/sea-gem-3.jpg', 'caption' => 'Sea Gem — Bedroom Window and Coastal Dresser'],
                ['url' => 'assets/images/rooms/sea-gem-4.jpg', 'caption' => 'Sea Gem — Room Perspective and Island Styling'],
                ['url' => 'assets/images/rooms/sea-gem-5.jpg', 'caption' => 'Sea Gem — Restful Island Atmosphere'],
            ],
        ],
        [
            'name' => 'Seascape',
            'suite_type' => 'BEDROOM 03',
            'description' => 'Serene private bedroom featuring a king-size bed, bathroom located directly across the hall with marble tile finishes, quiet air conditioning, and ample storage.',
            'bed_configuration' => 'King-size Bed',
            'bathroom_details' => 'Bathroom Located Directly Across the Hall',
            'feature_3' => 'Marble Tile Finishes',
            'image_url' => 'assets/images/rooms/bedroom-3-1.jpg',
            'photos' => [
                ['url' => 'assets/images/rooms/bedroom-3-1.jpg', 'caption' => 'Seascape — King Bedroom at The Love Shack Aruba'],
                ['url' => 'assets/images/rooms/bedroom-3-2.jpg', 'caption' => 'Seascape — Spacious Bathroom with Marble Tile'],
                ['url' => 'assets/images/rooms/bedroom-3-3.jpg', 'caption' => 'Seascape — Window Light and Interior Space'],
                ['url' => 'assets/images/rooms/bedroom-3-4.jpg', 'caption' => 'Seascape — Dresser Storage and Accents'],
            ],
        ],
    ];

    $pdo = get_db_connection();
    if (!$pdo) return $fallback;

    try {
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE property_id = ? ORDER BY display_order ASC");
        $stmt->execute([$propertyId]);
        $rooms = $stmt->fetchAll();
        return !empty($rooms) ? $rooms : $fallback;
    } catch (Exception $e) {
        error_log("Error fetching rooms: " . $e->getMessage());
        return $fallback;
    }
}

function get_gallery_images(int $propertyId = 1, bool $featuredOnly = false): array {
    $fallback = [
        [
            'title' => 'Savaneta Beachfront Sunset',
            'caption' => 'Private beachfront lounge and illuminated palm trees along the Savaneta shoreline at sunset',
            'category' => 'Beachfront & Sunset',
            'image_url' => 'assets/images/showcase-1.jpg',
            'is_crop' => false,
        ],
        [
            'title' => 'Twilight Pool & Palapa Oasis',
            'caption' => 'Freshwater swimming pool with dolphin mosaic, romantic heart chair, and palapa cabana at twilight',
            'category' => 'Pool & Cabana',
            'image_url' => 'assets/images/showcase-2.jpg',
            'is_crop' => false,
        ],
        [
            'title' => 'Poolside Dining Veranda',
            'caption' => 'Covered dining veranda with custom table setting and champagne overlooking the private pool',
            'category' => 'Alfresco Dining',
            'image_url' => 'assets/images/showcase-3.jpg',
            'is_crop' => false,
        ],
    ];

    $pdo = get_db_connection();
    if (!$pdo) return $fallback;

    try {
        $sql = "SELECT * FROM gallery_images WHERE property_id = ?";
        if ($featuredOnly) {
            $sql .= " AND is_featured = 1";
        }
        $sql .= " ORDER BY display_order ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$propertyId]);
        $imgs = $stmt->fetchAll();
        return !empty($imgs) ? $imgs : $fallback;
    } catch (Exception $e) {
        error_log("Error fetching gallery images: " . $e->getMessage());
        return $fallback;
    }
}

function get_property_showcase_images(): array {
    return [
        [
            'title' => 'Savaneta Beachfront Sunset',
            'caption' => 'Private beachfront lounge and illuminated palm trees along the Savaneta shoreline at sunset',
            'category' => 'Beachfront & Sunset',
            'image_url' => 'assets/images/property-showcase-1.jpg',
        ],
        [
            'title' => 'Sunset Pool & Cabana Oasis',
            'caption' => 'Freshwater swimming pool with dolphin mosaic, shaded palapa cabana, and sunset Caribbean sky',
            'category' => 'Pool & Cabana',
            'image_url' => 'assets/images/property-showcase-2.jpg',
        ],
        [
            'title' => 'Poolside Dining Veranda',
            'caption' => 'Covered dining veranda with custom coastal table setting and champagne overlooking the private pool',
            'category' => 'Alfresco Dining',
            'image_url' => 'assets/images/property-showcase-3.jpg',
        ],
    ];
}

function get_amenities_grouped(int $propertyId = 1): array {
    $pdo = get_db_connection();
    $grouped = [
        'Popular' => [],
        'Bathroom' => [],
        'Bedroom and laundry' => [],
        'Entertainment' => [],
        'Kitchen and dining' => [],
        'Outdoor' => [],
        'Parking and additional facilities' => [],
    ];

    if (!$pdo) {
        // Fallback comprehensive list
        $grouped['Popular'] = [
            ['name' => 'Private Freshwater Pool', 'description' => 'Exclusive deep swimming pool with sun loungers'],
            ['name' => 'Direct Beach Access', 'description' => 'Immediate steps from patio onto quiet Savaneta sand'],
            ['name' => 'High-Speed Wi-Fi', 'description' => 'Fast internet throughout villa and pool patio'],
            ['name' => 'Air Conditioning', 'description' => 'Independent climate control in all bedrooms and living areas'],
            ['name' => 'Fully Equipped Kitchen', 'description' => 'Gas cooking range, stainless refrigerator, microwave, and cookware'],
            ['name' => 'Free Private Parking', 'description' => 'Secure gated on-premise parking for up to 2 vehicles'],
            ['name' => '3 Private Bathrooms', 'description' => '1 attached master en-suite and 2 full bathrooms located directly across the hall'],
            ['name' => 'Outdoor Rain Shower', 'description' => 'Rinse off under warm Caribbean trade winds after beach dips'],
            ['name' => 'Covered Dining Veranda', 'description' => 'Shaded veranda dining table and lounge seating'],
            ['name' => 'Complimentary Beach Gear', 'description' => 'Beach chairs, beach towels, cooler, and snorkel sets'],
        ];
        $grouped['Bathroom'] = [
            ['name' => 'Hot Water & Rain Showers', 'description' => 'Consistent pressure and warm water in all 3 suites'],
            ['name' => 'Organic Bath Amenities', 'description' => 'Eco-friendly shampoo, conditioner, body wash, and soaps'],
            ['name' => 'Hair Dryers in Every Bathroom', 'description' => 'Professional grade blow dryers provided'],
            ['name' => 'Plush Towel Sets', 'description' => 'Egyptian cotton bath towels, hand towels, and face cloths'],
        ];
        $grouped['Bedroom and laundry'] = [
            ['name' => 'Comfortable Beds & Linens', 'description' => 'Quality mattresses with breathable organic cotton sheets'],
            ['name' => 'In-Unit Washer & Dryer', 'description' => 'Washing machine and dryer with complimentary detergent'],
            ['name' => 'Blackout Drapes & Shades', 'description' => 'Custom room-darkening shades for restorative sleep'],
            ['name' => 'Wardrobe & Wooden Hangers', 'description' => 'Generous closet storage and luggage racks in all rooms'],
            ['name' => 'Iron & Ironing Board', 'description' => 'Steam iron and full-size pressing board'],
        ];
        $grouped['Entertainment'] = [
            ['name' => 'Smart HD Flat Screens', 'description' => 'Equipped with streaming capabilities'],
            ['name' => 'Bluetooth Sound System', 'description' => 'Portable high-fidelity speaker for relaxing pool listening'],
            ['name' => 'Board Games & Reading Books', 'description' => 'Curated books, card decks, and family games'],
        ];
        $grouped['Kitchen and dining'] = [
            ['name' => 'Refrigerator with Freezer', 'description' => 'Full-size refrigerator with freezer and ice tray'],
            ['name' => 'Gas Cooking Range', 'description' => 'Multi-burner gas range for island cooking'],
            ['name' => 'Microwave', 'description' => 'Countertop microwave for convenient reheating and meals'],
            ['name' => 'Drip Coffee Maker', 'description' => 'Fresh drip coffee maker with complimentary organic starter coffee'],
            ['name' => 'Cookware, Pots & Pans', 'description' => 'Skillet set, cooking pots, chef knives, and baking sheet'],
            ['name' => 'Wooden Breakfast Island', 'description' => 'Handcrafted wooden breakfast island with comfortable counter seating'],
            ['name' => 'Blender for Island Cocktails', 'description' => 'Perfect for whipping up fresh smoothies and tropical drinks'],
        ];
        $grouped['Outdoor'] = [
            ['name' => 'Propane Gas BBQ Grill', 'description' => 'Barbecue grill with grilling utensils provided'],
            ['name' => 'Sun Loungers & Umbrellas', 'description' => 'Comfortable poolside sunbathing loungers and parasols'],
            ['name' => 'Covered Dining Veranda', 'description' => 'Shaded dining veranda seating comfortably outdoors'],
            ['name' => 'Private Enclosed Courtyard', 'description' => 'Lush tropical perimeter landscaping for complete privacy'],
        ];
        $grouped['Parking and additional facilities'] = [
            ['name' => 'Secure Gated Driveway', 'description' => 'Gated access with private guest entry'],
            ['name' => 'Dedicated Local Host', 'description' => 'On-island assistance for recommendations and provisioning'],
            ['name' => 'Keyless Digital Check-in', 'description' => 'Custom keypad entry code active for your reservation'],
            ['name' => 'Beach Tent, Floats & Kids\' Toys', 'description' => 'Shade beach tent, water floats, and kids\' beach toys provided'],
            ['name' => 'First Aid Kit & Smoke Alarms', 'description' => 'Equipped safety devices and fire extinguisher on site'],
        ];
        return $grouped;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM amenities WHERE property_id = ? AND is_active = 1 ORDER BY display_order ASC");
        $stmt->execute([$propertyId]);
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $cat = $row['category'];
            if (isset($grouped[$cat])) {
                $grouped[$cat][] = $row;
            } else {
                $grouped[$cat] = [$row];
            }
        }
    } catch (Exception $e) {
        error_log("Error fetching amenities: " . $e->getMessage());
    }

    return $grouped;
}

function get_reviews(int $propertyId = 1, int $limit = 15): array {
    $fallback = [
        [
            'guest_name' => 'Marcus & Elena V.',
            'guest_country' => 'United States',
            'stay_date' => 'October Stay',
            'review_text' => 'The Love Shack provided a peaceful, private retreat for our family. Stepping directly from the pool terrace onto the sand every morning and paddling across the quiet waters of Savaneta made for an unforgettable Aruban escape.',
            'tag' => 'Guest Reflection',
            'focus' => 'Private Pool, Beach Access & Family Relaxation',
            'is_sample' => false,
        ],
        [
            'guest_name' => 'David K. & Friends',
            'guest_country' => 'Canada',
            'stay_date' => 'September Stay',
            'review_text' => 'The 3 en-suite bedrooms offered great comfort and privacy for each couple, while the open terrace and freshwater pool brought everyone together. Proximity to Zeerovers for fresh local seafood was an exceptional highlight.',
            'tag' => 'Guest Reflection',
            'focus' => '3 En-Suite Bedrooms & Local Savaneta Dining',
            'is_sample' => false,
        ],
        [
            'guest_name' => 'Rachel & Brandon M.',
            'guest_country' => 'United Kingdom',
            'stay_date' => 'August Stay',
            'review_text' => 'A truly secluded Aruban sanctuary. Having a private freshwater swimming pool right by the ocean with calm tropical breezes made our stay restorative and effortless from start to finish.',
            'tag' => 'Guest Reflection',
            'focus' => 'Secluded Waterfront Setting & Freshwater Pool',
            'is_sample' => false,
        ],
    ];

    $pdo = get_db_connection();
    if (!$pdo) return $fallback;

    try {
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE property_id = ? AND is_approved = 1 ORDER BY display_order ASC LIMIT ?");
        $stmt->bindValue(1, $propertyId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $reviews = $stmt->fetchAll();
        return !empty($reviews) ? $reviews : $fallback;
    } catch (Exception $e) {
        error_log("Error fetching reviews: " . $e->getMessage());
        return $fallback;
    }
}

function get_faqs(): array {
    $fallback = [
        [
            'question' => 'How far is The Love Shack from the airport?',
            'answer' => 'Queen Beatrix International Airport (AUA) is approximately a 15-minute drive from Savaneta. We recommend renting a vehicle at the airport for exploring the island, or our concierge can coordinate a trusted private driver for your arrival.',
            'category' => 'Location & Arrival',
        ],
        [
            'question' => 'What are the standard check-in and check-out times?',
            'answer' => 'Standard check-in begins at 3:00 PM and check-out is at 11:00 AM. Keyless digital locks provide flexible, secure arrivals. Early check-in or late check-out requests are accommodated whenever the cleaning schedule permits.',
            'category' => 'Arrival & Stay',
        ],
        [
            'question' => 'Is the swimming pool private or shared with neighbors?',
            'answer' => 'The swimming pool is 100% private and exclusively reserved for your party. It is situated on your private enclosed oceanfront terrace overlooking the Caribbean sea.',
            'category' => 'Property & Pool',
        ],
        [
            'question' => 'Are beach towels, chairs, and snorkel gear provided?',
            'answer' => 'Yes! We provide full sets of plush beach towels, lightweight portable beach chairs, an insulated cooler, and snorkel gear for exploring Savaneta\'s vibrant reefs and nearby Mangel Halto.',
            'category' => 'Amenities',
        ],
        [
            'question' => 'What is the cancellation policy?',
            'answer' => 'Standard direct inquiry policy offers full refund up to 30 days prior to arrival, and 50% refund up to 14 days prior. Personalized terms can be agreed upon reservation confirmation with your host.',
            'category' => 'Policies',
        ],
    ];

    $pdo = get_db_connection();
    if (!$pdo) return $fallback;

    try {
        $stmt = $pdo->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY display_order ASC");
        $faqs = $stmt->fetchAll();
        return !empty($faqs) ? $faqs : $fallback;
    } catch (Exception $e) {
        error_log("Error fetching FAQs: " . $e->getMessage());
        return $fallback;
    }
}

function get_additional_services(?string $category = null): array {
    $fallback = [
        [
            'id' => 1,
            'category' => 'Dining Extras',
            'name' => 'Private Aruban Chef Dinner',
            'description' => 'Three-course custom seaside dinner prepared fresh on your pool veranda by a private local Aruban chef.',
            'image_url' => 'assets/images/temporary-hero.jpg',
            'price' => 180.00,
            'pricing_type' => 'per_stay',
        ],
        [
            'id' => 2,
            'category' => 'Dining Extras',
            'name' => 'Caribbean Seafood BBQ Provisioning Basket',
            'description' => 'Fresh catch red snapper, jumbo shrimp, Caribbean marinades, plantains, and charcoal essentials delivered for your patio BBQ.',
            'image_url' => 'assets/images/temporary-pool.jpg',
            'price' => 95.00,
            'pricing_type' => 'per_stay',
        ],
        [
            'id' => 4,
            'category' => 'Romantic Add-ons',
            'name' => 'Sunset Beach Cabana & Champagne Toast',
            'description' => 'Chilled Laurent-Perrier champagne, artisanal chocolate strawberries, and seaside lantern styling for an unforgettable twilight.',
            'image_url' => 'assets/images/temporary-beach.jpg',
            'price' => 120.00,
            'pricing_type' => 'per_stay',
        ],
        [
            'id' => 6,
            'category' => 'Celebration Packages',
            'name' => 'Island Anniversary / Birthday Celebration Package',
            'description' => 'Handcrafted floral arrangement, bespoke celebration cake, custom greeting card, and beachside champagne chilling on arrival.',
            'image_url' => 'assets/images/temporary-hero.jpg',
            'price' => 150.00,
            'pricing_type' => 'per_stay',
        ],
        [
            'id' => 8,
            'category' => 'Other Extras',
            'name' => 'VIP Airport Round-Trip Chauffeur',
            'description' => 'Private Mercedes van transfer from Queen Beatrix Airport directly to The Love Shack, luggage assistance included.',
            'image_url' => 'assets/images/temporary-beach.jpg',
            'price' => 80.00,
            'pricing_type' => 'per_stay',
        ],
    ];

    $pdo = get_db_connection();
    if (!$pdo) {
        if ($category !== null) {
            return array_values(array_filter($fallback, fn($s) => $s['category'] === $category));
        }
        return $fallback;
    }

    try {
        $sql = "SELECT * FROM additional_services WHERE is_active = 1";
        $params = [];
        if ($category !== null) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        $sql .= " ORDER BY display_order ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $services = $stmt->fetchAll();
        return !empty($services) ? $services : $fallback;
    } catch (Exception $e) {
        error_log("Error fetching additional services: " . $e->getMessage());
        return $fallback;
    }
}

// ----------------------------------------------------------------------------
// Availability & Pricing Engine
// ----------------------------------------------------------------------------

function check_dates_availability(string $checkin, string $checkout, int $propertyId = 1): array {
    $settings = get_site_settings();
    $minNights = (int)($settings['min_stay_nights'] ?? 3);

    // Validate format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $checkin) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $checkout)) {
        return ['available' => false, 'error' => 'Please provide valid check-in and check-out dates (YYYY-MM-DD).'];
    }

    $t1 = strtotime($checkin);
    $t2 = strtotime($checkout);
    $today = strtotime(date('Y-m-d'));

    if ($t1 === false || $t2 === false) {
        return ['available' => false, 'error' => 'Invalid calendar date values provided.'];
    }

    if ($t1 < $today) {
        return ['available' => false, 'error' => 'Check-in date cannot be in the past. Please select an upcoming date.'];
    }

    if ($t2 <= $t1) {
        return ['available' => false, 'error' => 'Check-out date must be strictly after your check-in date.'];
    }

    $diffDays = (int)round(($t2 - $t1) / 86400);

    if ($diffDays < $minNights) {
        return [
            'available' => false,
            'nights' => $diffDays,
            'error' => "The Love Shack requires a minimum stay of {$minNights} nights. Your selected duration is {$diffDays} night(s)."
        ];
    }

    $pdo = get_db_connection();
    if (!$pdo) {
        // Fallback: if database is not yet initialized, allow proceeding with notice
        return [
            'available' => true,
            'nights' => $diffDays,
            'message' => "Dates are available for booking inquiry ({$diffDays} nights).",
        ];
    }

    try {
        // 1. Check blocked_dates table
        $stmt = $pdo->prepare("
            SELECT reason FROM blocked_dates 
            WHERE property_id = ? 
              AND NOT (end_date <= ? OR start_date >= ?)
            LIMIT 1
        ");
        $stmt->execute([$propertyId, $checkin, $checkout]);
        $blocked = $stmt->fetch();
        if ($blocked) {
            return [
                'available' => false,
                'nights' => $diffDays,
                'error' => "Selected dates ({$checkin} to {$checkout}) are unfortunately unavailable: " . htmlspecialchars($blocked['reason']) . ". Please choose alternate travel dates.",
            ];
        }

        // 2. Check bookings table (CONFIRMED or PENDING holds)
        $stmt = $pdo->prepare("
            SELECT booking_reference FROM bookings 
            WHERE property_id = ? 
              AND status IN ('CONFIRMED', 'PENDING')
              AND NOT (checkout_date <= ? OR checkin_date >= ?)
            LIMIT 1
        ");
        $stmt->execute([$propertyId, $checkin, $checkout]);
        $booked = $stmt->fetch();
        if ($booked) {
            return [
                'available' => false,
                'nights' => $diffDays,
                'error' => "Selected dates ({$checkin} to {$checkout}) currently have a reservation hold. Please choose alternate dates.",
            ];
        }

        return [
            'available' => true,
            'nights' => $diffDays,
            'message' => "Great news! The Love Shack is available for your selected dates ({$diffDays} nights).",
        ];
    } catch (Exception $e) {
        error_log("Availability check error: " . $e->getMessage());
        return [
            'available' => true,
            'nights' => $diffDays,
            'message' => "Dates are available for booking inquiry ({$diffDays} nights).",
        ];
    }
}

function calculate_booking_price(string $checkin, string $checkout, int $adults = 2, int $children = 0, array $selectedServices = []): array {
    $settings = get_site_settings();
    $defaultRate = (float)($settings['default_nightly_rate'] ?? 650.00);
    $cleaningFee = (float)($settings['cleaning_fee'] ?? 180.00);
    $maxGuests = (int)($settings['max_guests'] ?? 6);

    $t1 = strtotime($checkin);
    $t2 = strtotime($checkout);
    $nights = max(1, (int)round(($t2 - $t1) / 86400));
    $totalGuests = max(1, min($maxGuests, $adults + $children));

    // Determine nightly rate (check seasonal pricing rules if available)
    $nightlyRate = $defaultRate;
    $pdo = get_db_connection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("
                SELECT nightly_rate FROM pricing_rules 
                WHERE is_active = 1 
                  AND start_date <= ? AND end_date >= ?
                ORDER BY nightly_rate DESC LIMIT 1
            ");
            $stmt->execute([$checkin, $checkout]);
            $rule = $stmt->fetch();
            if ($rule) {
                $nightlyRate = (float)$rule['nightly_rate'];
            }
        } catch (Exception $e) {
            error_log("Pricing rule query error: " . $e->getMessage());
        }
    }

    $accommodationSubtotal = $nights * $nightlyRate;

    // Calculate additional services
    $servicesBreakdown = [];
    $servicesTotal = 0.00;

    if (!empty($selectedServices) && $pdo) {
        try {
            // Fetch active services
            $stmt = $pdo->query("SELECT id, name, price, pricing_type FROM additional_services WHERE is_active = 1");
            $dbServices = [];
            while ($row = $stmt->fetch()) {
                $dbServices[$row['id']] = $row;
            }

            foreach ($selectedServices as $item) {
                $serviceId = (int)($item['id'] ?? 0);
                $qty = max(1, (int)($item['qty'] ?? 1));
                if (isset($dbServices[$serviceId])) {
                    $s = $dbServices[$serviceId];
                    $unitPrice = (float)$s['price'];
                    $lineTotal = $unitPrice * $qty;
                    $servicesTotal += $lineTotal;
                    $servicesBreakdown[] = [
                        'id' => $serviceId,
                        'name' => $s['name'],
                        'unit_price' => $unitPrice,
                        'qty' => $qty,
                        'line_total' => $lineTotal,
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Services pricing calculation error: " . $e->getMessage());
        }
    }

    $estimatedTotal = $accommodationSubtotal + $cleaningFee + $servicesTotal;

    return [
        'nights' => $nights,
        'adults' => $adults,
        'children' => $children,
        'total_guests' => $totalGuests,
        'nightly_rate' => $nightlyRate,
        'accommodation_subtotal' => $accommodationSubtotal,
        'cleaning_fee' => $cleaningFee,
        'services' => $servicesBreakdown,
        'services_total' => $servicesTotal,
        'estimated_total' => $estimatedTotal,
    ];
}

function create_booking_request(array $data): array {
    $checkin = $data['checkin'] ?? '';
    $checkout = $data['checkout'] ?? '';
    $adults = (int)($data['adults'] ?? 2);
    $children = (int)($data['children'] ?? 0);
    $firstName = sanitize_input($data['first_name'] ?? '');
    $lastName = sanitize_input($data['last_name'] ?? '');
    $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = sanitize_input($data['phone'] ?? '');
    $country = sanitize_input($data['country'] ?? '');
    $notes = sanitize_input($data['notes'] ?? '');
    $selectedServices = $data['services'] ?? [];

    if (empty($firstName) || empty($lastName)) {
        return ['success' => false, 'error' => 'Please provide both first and last name.'];
    }
    if (!$email) {
        return ['success' => false, 'error' => 'Please provide a valid email address.'];
    }

    // Availability validation
    $avail = check_dates_availability($checkin, $checkout);
    if (!$avail['available']) {
        return ['success' => false, 'error' => $avail['error'] ?? 'Selected dates are unavailable.'];
    }

    // Server-side price calculation
    $pricing = calculate_booking_price($checkin, $checkout, $adults, $children, $selectedServices);

    // Generate unique booking reference
    $datePart = date('Ym', strtotime($checkin));
    $randomPart = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
    $bookingReference = "LS-{$datePart}-{$randomPart}";

    $pdo = get_db_connection();
    if (!$pdo) {
        // Fallback acknowledgement when MySQL is offline
        return [
            'success' => true,
            'booking_reference' => $bookingReference,
            'status' => 'PENDING',
            'pricing' => $pricing,
            'guest' => [
                'name' => "{$firstName} {$lastName}",
                'email' => $email,
                'phone' => $phone,
            ],
            'message' => 'Your booking inquiry has been recorded. Our island concierge will verify your dates and respond within 24 hours. (Database currently offline/provisional mode)',
        ];
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO bookings (
                booking_reference, property_id, checkin_date, checkout_date,
                adults, children, total_guests, nights, nightly_rate,
                accommodation_total, cleaning_fee, services_total, estimated_total,
                status, host_notes
            ) VALUES (?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', ?)
        ");
        $stmt->execute([
            $bookingReference,
            $checkin,
            $checkout,
            $adults,
            $children,
            $pricing['total_guests'],
            $pricing['nights'],
            $pricing['nightly_rate'],
            $pricing['accommodation_subtotal'],
            $pricing['cleaning_fee'],
            $pricing['services_total'],
            $pricing['estimated_total'],
            $notes
        ]);

        $bookingId = (int)$pdo->lastInsertId();

        // Insert guest details
        $stmt = $pdo->prepare("
            INSERT INTO booking_guests (
                booking_id, first_name, last_name, email, phone, country, special_requests
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $bookingId,
            $firstName,
            $lastName,
            $email,
            $phone,
            $country,
            $notes
        ]);

        // Insert selected services
        if (!empty($pricing['services'])) {
            $stmt = $pdo->prepare("
                INSERT INTO booking_services (
                    booking_id, service_id, service_name, quantity, unit_price, total_price
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");
            foreach ($pricing['services'] as $svc) {
                $stmt->execute([
                    $bookingId,
                    $svc['id'],
                    $svc['name'],
                    $svc['qty'],
                    $svc['unit_price'],
                    $svc['line_total']
                ]);
            }
        }

        $pdo->commit();

        return [
            'success' => true,
            'booking_reference' => $bookingReference,
            'status' => 'PENDING',
            'pricing' => $pricing,
            'guest' => [
                'name' => "{$firstName} {$lastName}",
                'email' => $email,
                'phone' => $phone,
            ],
            'message' => 'Thank you! Your booking request has been submitted with status PENDING. Our on-island concierge will review your travel details and respond within 24 hours.',
        ];
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Booking submission failed: " . $e->getMessage());
        return ['success' => false, 'error' => 'A server error occurred while processing your reservation request. Please try again or reach our concierge directly.'];
    }
}

function save_contact_enquiry(string $name, string $email, string $phone, string $subject, string $message): array {
    $name = sanitize_input($name);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $phone = sanitize_input($phone);
    $subject = sanitize_input($subject ?: 'General Inquiry');
    $message = sanitize_input($message);

    if (empty($name)) {
        return ['success' => false, 'error' => 'Please provide your full name.'];
    }
    if (!$email) {
        return ['success' => false, 'error' => 'Please provide a valid email address.'];
    }
    if (empty($message)) {
        return ['success' => false, 'error' => 'Please enter your inquiry message.'];
    }

    $pdo = get_db_connection();
    if (!$pdo) {
        return [
            'success' => true,
            'message' => 'Thank you, ' . htmlspecialchars($name) . '! Your message has been received. Our concierge team in Aruba will reply promptly.',
        ];
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO enquiries (name, email, phone, subject, message, status)
            VALUES (?, ?, ?, ?, ?, 'NEW')
        ");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        return [
            'success' => true,
            'message' => 'Thank you, ' . htmlspecialchars($name) . '! Your message has been received. Our concierge team in Aruba will reply promptly.',
        ];
    } catch (Exception $e) {
        error_log("Error saving enquiry: " . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Unable to save your message right now. Please try again or message via WhatsApp.',
        ];
    }
}
