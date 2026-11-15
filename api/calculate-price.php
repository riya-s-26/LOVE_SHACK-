<?php
/**
 * THE LOVE SHACK — API: Calculate Dynamic Booking Price
 * GET or POST: checkin, checkout, adults, children, services (array)
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = $_SERVER['REQUEST_METHOD'] === 'POST' ? json_decode(file_get_contents('php://input'), true) ?: $_POST : $_GET;

$checkin = trim((string)($input['checkin'] ?? ''));
$checkout = trim((string)($input['checkout'] ?? ''));
$adults = (int)($input['adults'] ?? 2);
$children = (int)($input['children'] ?? 0);
$services = is_array($input['services'] ?? null) ? $input['services'] : [];

if (empty($checkin) || empty($checkout)) {
    json_response(['error' => 'Please provide both check-in and check-out dates.'], 400);
}

$avail = check_dates_availability($checkin, $checkout);
if (!$avail['available']) {
    json_response([
        'available' => false,
        'error' => $avail['error'] ?? 'Selected dates are unavailable.'
    ], 200);
}

$pricing = calculate_booking_price($checkin, $checkout, $adults, $children, $services);
$pricing['available'] = true;

json_response($pricing, 200);
