-- ============================================================================
-- THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (SAVANETA, ARUBA)
-- MySQL Database Schema & Initial Data
-- Compatible with MySQL 5.7+ / 8.0+ / MariaDB & phpMyAdmin
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `booking_services`;
DROP TABLE IF EXISTS `booking_guests`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `blocked_dates`;
DROP TABLE IF EXISTS `additional_services`;
DROP TABLE IF EXISTS `pricing_rules`;
DROP TABLE IF EXISTS `faqs`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `amenities`;
DROP TABLE IF EXISTS `gallery_images`;
DROP TABLE IF EXISTS `rooms`;
DROP TABLE IF EXISTS `properties`;
DROP TABLE IF EXISTS `site_settings`;
DROP TABLE IF EXISTS `enquiries`;
DROP TABLE IF EXISTS `admin_users`;
SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------
-- 1. Table: site_settings
-- ----------------------------------------------------------------------------
CREATE TABLE `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(64) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `setting_group` VARCHAR(32) NOT NULL DEFAULT 'general',
  `description` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('site_title', 'The Love Shack', 'general', 'Website brand name'),
('site_tagline', 'Luxury Beachfront Sanctuary · Savaneta, Aruba', 'general', 'Brand subtitle'),
('concierge_email', 'concierge@loveshackaruba.com', 'contact', 'Provisional concierge email'),
('concierge_phone', '+297 580 0000', 'contact', 'Provisional phone / WhatsApp'),
('property_location', 'Savaneta Beachfront, Aruba, Dutch Caribbean', 'contact', 'Physical location summary'),
('default_nightly_rate', '650.00', 'pricing', 'Standard base nightly rate in USD (Provisional)'),
('min_stay_nights', '3', 'pricing', 'Minimum consecutive nights required'),
('max_guests', '6', 'pricing', 'Maximum total guest capacity'),
('cleaning_fee', '180.00', 'pricing', 'Departure sanitization and turnover fee in USD (Provisional)'),
('service_tax_percent', '9.50', 'pricing', 'Aruba local lodging tax percentage (Provisional)'),
('checkin_time', '15:00', 'policies', 'Standard check-in time (3:00 PM)'),
('checkout_time', '11:00', 'policies', 'Standard check-out time (11:00 AM)'),
('cancellation_policy', 'Full refund up to 30 days prior to arrival. 50% refund between 30 and 14 days prior to arrival.', 'policies', 'Standard cancellation policy');

-- ----------------------------------------------------------------------------
-- 2. Table: properties
-- ----------------------------------------------------------------------------
CREATE TABLE `properties` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(128) NOT NULL,
  `slug` VARCHAR(64) NOT NULL UNIQUE,
  `tagline` VARCHAR(255) NULL,
  `hero_heading` VARCHAR(255) NOT NULL,
  `hero_description` TEXT NOT NULL,
  `hero_image` VARCHAR(255) NOT NULL,
  `intro_badge` VARCHAR(64) DEFAULT 'THE SANCTUARY · ARUBA',
  `intro_title` VARCHAR(255) NOT NULL,
  `intro_content` TEXT NOT NULL,
  `bedrooms` TINYINT NOT NULL DEFAULT 3,
  `bathrooms` TINYINT NOT NULL DEFAULT 3,
  `max_guests` TINYINT NOT NULL DEFAULT 6,
  `base_nightly_rate` DECIMAL(10,2) NOT NULL DEFAULT 650.00,
  `location_area` VARCHAR(128) NOT NULL DEFAULT 'Savaneta, Aruba',
  `map_lat` DECIMAL(10,7) DEFAULT 12.4419000,
  `map_lng` DECIMAL(10,7) DEFAULT -69.9328000,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `properties` (`id`, `name`, `slug`, `tagline`, `hero_heading`, `hero_description`, `hero_image`, `intro_badge`, `intro_title`, `intro_content`, `bedrooms`, `bathrooms`, `max_guests`, `base_nightly_rate`, `location_area`) VALUES
(1, 'The Love Shack', 'the-love-shack', 'Barefoot luxury beachfront villa with private freshwater pool', 'Your beachfront escape in Aruba.', 'An intimate beachfront hideaway where barefoot island living meets understated elegance. Wake to gentle Caribbean waves and step directly from your private pool terrace onto secluded Aruban sands.', 'assets/images/temporary-hero.jpg', 'THE SANCTUARY · ARUBA', 'Barefoot luxury on Aruba\'s peaceful southern coast.', 'Tucked along the peaceful coastline of Savaneta, The Love Shack redefines island ease. Designed for travelers seeking quiet luxury, generous open-air living spaces, and the soothing sounds of gentle Caribbean tides.\r\n\r\nUnlike the bustling high-rise resort strip in the north, Savaneta offers untouched authentic island charm, calm clear turquoise shallows, and some of Aruba\'s most celebrated waterfront seafood restaurants just moments away.', 3, 3, 6, 650.00, 'Savaneta Beachfront, Aruba');

-- ----------------------------------------------------------------------------
-- 3. Table: rooms
-- ----------------------------------------------------------------------------
CREATE TABLE `rooms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `suite_type` VARCHAR(64) NOT NULL,
  `description` TEXT NOT NULL,
  `bed_configuration` VARCHAR(128) NOT NULL,
  `bathroom_details` VARCHAR(128) NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `display_order` INT NOT NULL DEFAULT 1,
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rooms` (`property_id`, `name`, `suite_type`, `description`, `bed_configuration`, `bathroom_details`, `image_url`, `display_order`) VALUES
(1, 'Master Ocean Suite', 'Primary Oceanfront Suite', 'Expansive primary sanctuary boasting direct sliding door access to the lanai pool deck, custom king bed frame, and wake-up views of turquoise Caribbean shallows.', 'King Bed with Organic Linens', 'En-suite Dual Vanity & Rain Shower', 'assets/images/temporary-hero.jpg', 1),
(1, 'Garden Palm Suite', 'Secluded Garden Suite', 'Nestled overlooking tropical palm foliage. Features quiet air conditioning, blackout shades, and an airy coastal aesthetic for peaceful island slumber.', 'Queen Bed with Pillowtop Mattress', 'Private En-suite Bathroom & Stone Basin', 'assets/images/temporary-pool.jpg', 2),
(1, 'Lagoon Breeze Suite', 'Flexible Guest Suite', 'Flexible luxury bedroom suite adaptable for couples, families, or accompanying friends. Steps from the private indoor courtyard and outdoor shower.', 'Two Twin Beds or Unified King', 'En-suite Bath with Natural Stone Accents', 'assets/images/temporary-beach.jpg', 3);

-- ----------------------------------------------------------------------------
-- 4. Table: gallery_images
-- ----------------------------------------------------------------------------
CREATE TABLE `gallery_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL,
  `title` VARCHAR(128) NOT NULL,
  `caption` VARCHAR(255) NOT NULL,
  `category` VARCHAR(64) NOT NULL DEFAULT 'Villa & Pool',
  `image_url` VARCHAR(255) NOT NULL,
  `display_order` INT NOT NULL DEFAULT 1,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gallery_images` (`property_id`, `title`, `caption`, `category`, `image_url`, `display_order`, `is_featured`) VALUES
(1, 'Oceanfront Lanai & Pool', 'Direct beachfront swimming pool with sun lounger terrace and sunset ocean view', 'Exterior', 'assets/images/temporary-hero.jpg', 1, 1),
(1, 'Private Freshwater Pool', 'Shaded palm terrace, open-air sun deck and tropical garden sanctuary', 'Pool & Garden', 'assets/images/temporary-pool.jpg', 2, 1),
(1, 'Savaneta Caribbean Beach', 'Gentle turquoise shallows steps from your private villa gates', 'Beach & Ocean', 'assets/images/temporary-beach.jpg', 3, 1);

-- ----------------------------------------------------------------------------
-- 5. Table: amenities
-- ----------------------------------------------------------------------------
CREATE TABLE `amenities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL,
  `category` ENUM('Popular','Bathroom','Bedroom and laundry','Entertainment','Kitchen and dining','Outdoor','Parking and additional facilities') NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `description` VARCHAR(255) NULL,
  `icon_svg` TEXT NULL,
  `is_popular` TINYINT(1) NOT NULL DEFAULT 0,
  `display_order` INT NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `amenities` (`property_id`, `category`, `name`, `description`, `is_popular`, `display_order`) VALUES
(1, 'Popular', 'Private Freshwater Pool', 'Exclusive deep swimming pool with sun loungers', 1, 1),
(1, 'Popular', 'Direct Beach Access', 'Immediate steps from patio onto quiet Savaneta sand', 1, 2),
(1, 'Popular', 'High-Speed Wi-Fi', 'Fast internet throughout villa and pool patio', 1, 3),
(1, 'Popular', 'Air Conditioning', 'Independent climate control in all bedrooms and living areas', 1, 4),
(1, 'Popular', 'Fully Equipped Kitchen', 'Gas range, stainless refrigerator, microwave, and cookware', 1, 5),
(1, 'Popular', 'Free Private Parking', 'Secure gated on-premise parking for up to 2 vehicles', 1, 6),
(1, 'Popular', '3 Private En-Suite Bathrooms', 'Dedicated bathroom for every bedroom with hot water', 1, 7),
(1, 'Popular', 'Outdoor Rain Shower', 'Rinse off under warm Caribbean trade winds after beach dips', 1, 8),
(1, 'Popular', 'Oceanview Covered Lanai', 'Shaded veranda dining table and lounge seating', 1, 9),
(1, 'Popular', 'Complimentary Beach Gear', 'Beach chairs, beach towels, cooler, and snorkel sets', 1, 10),

(1, 'Bathroom', 'Hot Water & Rain Showers', 'Consistent pressure and warm water in all 3 suites', 0, 11),
(1, 'Bathroom', 'Organic Bath Amenities', 'Eco-friendly shampoo, conditioner, body wash and soaps', 0, 12),
(1, 'Bathroom', 'Hair Dryers in Every Bathroom', 'Professional grade blow dryers provided', 0, 13),
(1, 'Bathroom', 'Plush Towel Sets', 'Egyptian cotton bath towels, hand towels, and face cloths', 0, 14),

(1, 'Bedroom and laundry', 'Premium King & Queen Mattresses', 'High-density memory foam with breathable organic cotton sheets', 0, 15),
(1, 'Bedroom and laundry', 'In-Unit Washer & Dryer', 'Full-size washing machine and dryer with complimentary detergent', 0, 16),
(1, 'Bedroom and laundry', 'Blackout Drapes & Shades', 'Custom room-darkening shades for restorative sleep', 0, 17),
(1, 'Bedroom and laundry', 'Wardrobe & Wooden Hangers', 'Generous closet storage and luggage racks in all rooms', 0, 18),
(1, 'Bedroom and laundry', 'Iron & Ironing Board', 'Steam iron and full-size pressing board', 0, 19),

(1, 'Entertainment', 'Smart HD Flat Screens', 'Equipped with Netflix, Apple TV, and streaming capability', 0, 20),
(1, 'Entertainment', 'Bluetooth Sound System', 'Portable high-fidelity speaker for relaxing pool listening', 0, 21),
(1, 'Entertainment', 'Board Games & Reading Library', 'Curated Caribbean books, card decks, and family games', 0, 22),

(1, 'Kitchen and dining', 'Refrigerator with Ice Maker', 'Full-size double door fridge with filtered water', 0, 23),
(1, 'Kitchen and dining', 'Dishwasher & Pods', 'Whisper-quiet dishwasher for effortless post-meal clean-up', 0, 24),
(1, 'Kitchen and dining', 'Nespresso & Drip Coffee Bar', 'Coffee maker with complimentary starter pods and organic sugar', 0, 25),
(1, 'Kitchen and dining', 'Blender & Citrus Juicer', 'Perfect for preparing fresh tropical smoothies and cocktails', 0, 26),
(1, 'Kitchen and dining', 'Chef\'s Knife Set & Cookware', 'Stainless pans, baking trays, wine glasses, and champagne flutes', 0, 27),
(1, 'Kitchen and dining', 'Dining Tables (Indoor & Outdoor)', 'Accommodates 6 guests comfortably inside or on the ocean lanai', 0, 28),

(1, 'Outdoor', 'Propane Gas BBQ Grill', 'High-output stainless barbecue with grilling utensils provided', 0, 29),
(1, 'Outdoor', 'Double Daybed & Chaise Loungers', 'Comfortable cushioned outdoor sunbathing furniture', 0, 30),
(1, 'Outdoor', 'Palapa Thatched Shade', 'Natural island thatch shelter providing cool afternoon breeze', 0, 31),
(1, 'Outdoor', 'Private Enclosed Courtyard', 'Lush tropical perimeter fencing for complete privacy', 0, 32),

(1, 'Parking and additional facilities', 'Secure Gated Driveway', 'Motorized gate access with private guest key fob', 0, 33),
(1, 'Parking and additional facilities', 'Dedicated Local Host Concierge', 'On-island assistance for recommendations and provisioning', 0, 34),
(1, 'Parking and additional facilities', 'Keyless Digital Check-in', 'Custom secure keypad entry code active for your reservation', 0, 35),
(1, 'Parking and additional facilities', 'First Aid Kit & Smoke Alarms', 'Fully equipped safety devices and fire extinguisher on site', 0, 36);

-- ----------------------------------------------------------------------------
-- 6. Table: reviews
-- ----------------------------------------------------------------------------
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL,
  `guest_name` VARCHAR(128) NOT NULL,
  `guest_country` VARCHAR(64) DEFAULT 'United States',
  `rating` DECIMAL(2,1) NOT NULL DEFAULT 5.0,
  `stay_date` VARCHAR(32) NOT NULL,
  `review_text` TEXT NOT NULL,
  `platform` VARCHAR(32) NOT NULL DEFAULT 'Airbnb',
  `is_approved` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` (`property_id`, `guest_name`, `guest_country`, `rating`, `stay_date`, `review_text`, `platform`, `display_order`) VALUES
(1, 'Marcus & Elena V.', 'United States', 5.0, 'October 2026', 'The Love Shack exceeded every expectation our family had. Stepping directly from the private pool terrace onto the sand every morning was paradise. Having kayaks and paddleboards right there made paddling over to Sea Glass Island an absolute highlight. Traci and Alexandra were incredible hosts—super responsive and helpful with airport pickups and local dining. We can\'t wait to return!', 'Airbnb', 1),
(1, 'David K. & Friends', 'Canada', 5.0, 'September 2026', 'Five stars aren\'t enough for The Love Shack. The 3 en-suite bedrooms gave each couple complete privacy, while the open living area and pool deck brought everyone together. Alexandra greeted us at check-in and gave fantastic local recommendations—dinner at Zeerovers and Flying Fishbone just minutes away. Flawless stay from start to finish.', 'Airbnb', 2),
(1, 'Rachel & Brandon M.', 'United Kingdom', 5.0, 'August 2026', 'We celebrated our anniversary at The Love Shack and couldn\'t have asked for a better sanctuary. Quiet, gated, private, and romantic. The sea breeze flowing through the house and the private outdoor rain shower after beach dips made it feel like our own private boutique resort. Highly recommended!', 'Airbnb', 3),
(1, 'Sarah & Jonathan P.', 'United States', 5.0, 'July 2026', 'Everything about The Love Shack is top tier. The kitchen is fully equipped for making breakfast or grilling on the patio BBQ. Snorkeling equipment and beach chairs were in great condition. The location in Savaneta is tranquil and safe, yet only 10-15 minutes from the airport. Truly one of the best Airbnbs we\'ve ever stayed at.', 'Airbnb', 4),
(1, 'Henrik & Lisa T.', 'Netherlands', 5.0, 'June 2026', 'We have visited Aruba four times, but staying at The Love Shack in Savaneta was by far our favorite trip. The privacy of having your own beach and pool cannot be overstated. Alexandra arranged a wonderful private chef dinner for us on the patio. Worth every penny.', 'Airbnb', 5),
(1, 'Michael R.', 'United States', 5.0, 'May 2026', 'The view from the pool deck is mesmerizing. Calm water, incredible sunsets, and absolute peace. The villa was spotless and equipped with everything you could ever need. We barely left the property during our 7-night stay. Pure bliss.', 'Airbnb', 6);

-- ----------------------------------------------------------------------------
-- 7. Table: faqs
-- ----------------------------------------------------------------------------
CREATE TABLE `faqs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `category` VARCHAR(64) NOT NULL DEFAULT 'General',
  `display_order` INT NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `faqs` (`question`, `answer`, `category`, `display_order`) VALUES
('How far is The Love Shack from the airport?', 'Queen Beatrix International Airport (AUA) is approximately a 15-minute drive from Savaneta. We recommend renting a vehicle at the airport for exploring the island, or our concierge can coordinate a trusted private driver for your arrival.', 'Location & Arrival', 1),
('What are the standard check-in and check-out times?', 'Standard check-in begins at 3:00 PM and check-out is at 11:00 AM. Keyless digital locks provide flexible, secure arrivals. Early check-in or late check-out requests are accommodated whenever the cleaning schedule permits.', 'Arrival & Stay', 2),
('Is the swimming pool private or shared with neighbors?', 'The swimming pool is 100% private and exclusively reserved for your party. It is situated on your private enclosed oceanfront terrace overlooking the Caribbean sea.', 'Property & Pool', 3),
('Are beach towels, chairs, and snorkel gear provided?', 'Yes! We provide full sets of plush beach towels, lightweight portable beach chairs, an insulated cooler, and snorkel gear for exploring Savaneta\'s vibrant reefs and nearby Mangel Halto.', 'Amenities', 4),
('What is the cancellation policy?', 'Standard direct inquiry policy offers full refund up to 30 days prior to arrival, and 50% refund up to 14 days prior. Personalized terms can be agreed upon reservation confirmation with your host.', 'Policies', 5);

-- ----------------------------------------------------------------------------
-- 8. Table: pricing_rules
-- ----------------------------------------------------------------------------
CREATE TABLE `pricing_rules` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(128) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `nightly_rate` DECIMAL(10,2) NOT NULL,
  `min_stay_nights` TINYINT NOT NULL DEFAULT 3,
  `note` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pricing_rules` (`title`, `start_date`, `end_date`, `nightly_rate`, `min_stay_nights`, `note`) VALUES
('Standard High Season', '2026-11-01', '2026-12-19', 650.00, 3, 'Provisional standard winter rate'),
('Holiday Festive Season', '2026-12-20', '2027-01-05', 950.00, 5, 'Provisional festive peak rate'),
('Winter Sunshine Season', '2027-01-06', '2027-04-30', 750.00, 4, 'Provisional Caribbean peak season'),
('Summer Island Serenity', '2027-05-01', '2027-10-31', 580.00, 3, 'Provisional summer tranquil rate');

-- ----------------------------------------------------------------------------
-- 9. Table: additional_services
-- ----------------------------------------------------------------------------
CREATE TABLE `additional_services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` ENUM('Dining Extras','Romantic Add-ons','Celebration Packages','Other Extras') NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(255) NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `pricing_type` ENUM('per_stay','per_night','per_guest') NOT NULL DEFAULT 'per_stay',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `additional_services` (`category`, `name`, `description`, `image_url`, `price`, `pricing_type`, `display_order`) VALUES
('Dining Extras', 'Private Aruban Chef Dinner', 'Three-course custom seaside dinner prepared fresh on your pool lanai by a private local Aruban chef.', 'assets/images/temporary-hero.jpg', 180.00, 'per_stay', 1),
('Dining Extras', 'Caribbean Seafood BBQ Provisioning Basket', 'Fresh catch red snapper, jumbo shrimp, Caribbean marinades, plantains, and charcoal essentials delivered for your patio BBQ.', 'assets/images/temporary-pool.jpg', 95.00, 'per_stay', 2),
('Dining Extras', 'Daily Fresh Tropical Breakfast Basket', 'Freshly baked Dutch pastries, local fruits, Aruban jams, artisanal cheeses, and farm-fresh eggs delivered to your door.', 'assets/images/temporary-beach.jpg', 45.00, 'per_stay', 3),

('Romantic Add-ons', 'Sunset Beach Cabana & Champagne Toast', 'Chilled Laurent-Perrier champagne, artisanal chocolate strawberries, and seaside lantern styling for an unforgettable twilight.', 'assets/images/temporary-beach.jpg', 120.00, 'per_stay', 4),
('Romantic Add-ons', 'Couples Beachside Massage (60 min)', 'Restorative relaxation massage delivered side-by-side on your private ocean deck with certified Aruban spa therapists.', 'assets/images/temporary-pool.jpg', 160.00, 'per_stay', 5),

('Celebration Packages', 'Island Anniversary / Birthday Celebration Package', 'Handcrafted floral arrangement, bespoke celebration cake, custom greeting card, and beachside champagne chilling on arrival.', 'assets/images/temporary-hero.jpg', 150.00, 'per_stay', 6),
('Celebration Packages', 'Aruban Welcome Rum & Charcuterie Board', 'Boutique aged rum bottle, artisanal cheeses, cured meats, island crisps, and dried tropical fruits waiting in the villa.', 'assets/images/temporary-pool.jpg', 65.00, 'per_stay', 7),

('Other Extras', 'VIP Airport Round-Trip Chauffeur', 'Private Mercedes van transfer from Queen Beatrix Airport directly to The Love Shack, luggage assistance included.', 'assets/images/temporary-beach.jpg', 80.00, 'per_stay', 8),
('Other Extras', 'Guided Sea Glass Island Kayak Tour', 'Two-hour guided mangrove and reef excursion paddling directly from The Love Shack beach to historic Sea Glass Island.', 'assets/images/temporary-beach.jpg', 75.00, 'per_stay', 9);

-- ----------------------------------------------------------------------------
-- 10. Table: bookings
-- ----------------------------------------------------------------------------
CREATE TABLE `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_reference` VARCHAR(32) NOT NULL UNIQUE,
  `property_id` INT NOT NULL DEFAULT 1,
  `checkin_date` DATE NOT NULL,
  `checkout_date` DATE NOT NULL,
  `adults` TINYINT NOT NULL DEFAULT 2,
  `children` TINYINT NOT NULL DEFAULT 0,
  `total_guests` TINYINT NOT NULL DEFAULT 2,
  `nights` INT NOT NULL,
  `nightly_rate` DECIMAL(10,2) NOT NULL,
  `accommodation_total` DECIMAL(10,2) NOT NULL,
  `cleaning_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `services_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `estimated_total` DECIMAL(10,2) NOT NULL,
  `status` ENUM('PENDING','CONFIRMED','DECLINED','CANCELLED') NOT NULL DEFAULT 'PENDING',
  `host_notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_dates` (`checkin_date`, `checkout_date`),
  INDEX `idx_status` (`status`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 11. Table: booking_guests
-- ----------------------------------------------------------------------------
CREATE TABLE `booking_guests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `first_name` VARCHAR(64) NOT NULL,
  `last_name` VARCHAR(64) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `phone` VARCHAR(32) NULL,
  `country` VARCHAR(64) NULL,
  `special_requests` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 12. Table: booking_services
-- ----------------------------------------------------------------------------
CREATE TABLE `booking_services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `service_name` VARCHAR(128) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `additional_services`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 13. Table: blocked_dates
-- ----------------------------------------------------------------------------
CREATE TABLE `blocked_dates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL DEFAULT 1,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `reason` VARCHAR(255) NOT NULL DEFAULT 'Host Maintenance or Direct Hold',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_blocked` (`start_date`, `end_date`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample realistic holds for calendar availability verification
INSERT INTO `blocked_dates` (`property_id`, `start_date`, `end_date`, `reason`) VALUES
(1, '2026-11-20', '2026-11-25', 'Confirmed Private Guest Stay'),
(1, '2026-12-24', '2027-01-02', 'Confirmed Holiday Reservation');

-- ----------------------------------------------------------------------------
-- 14. Table: enquiries
-- ----------------------------------------------------------------------------
CREATE TABLE `enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(128) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `phone` VARCHAR(32) NULL,
  `subject` VARCHAR(128) NOT NULL DEFAULT 'General Inquiry',
  `message` TEXT NOT NULL,
  `status` ENUM('NEW','READ','RESPONDED','ARCHIVED') NOT NULL DEFAULT 'NEW',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_enquiry_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample initial enquiry for admin dashboard testing
INSERT INTO `enquiries` (`name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
('Sarah Miller', 'sarah.miller@example.com', '+1 555 234 5678', 'Booking Dates & Availability', 'Hello! We are looking to book 5 nights in January for our anniversary. Can you confirm if late checkout is possible?', 'NEW');

-- ----------------------------------------------------------------------------
-- 15. Table: admin_users
-- ----------------------------------------------------------------------------
CREATE TABLE `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(64) NOT NULL UNIQUE,
  `email` VARCHAR(128) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(128) NOT NULL,
  `role` VARCHAR(32) NOT NULL DEFAULT 'admin',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initial default administrator:
-- Username: admin
-- Password default: LoveShack2026! (hashed with BCRYPT)
-- NOTE: In production or upon first login, the administrator must change their credentials via /admin/setup.php or profile.
INSERT INTO `admin_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`) VALUES
(1, 'admin', 'admin@loveshackaruba.com', '$2y$10$w6lK.wV5u6qYj31zPjWdKuvqV7mPz5q4Z8H.u5L6xTj9eQk6D6E.m', 'Love Shack Concierge Administrator', 'admin');
