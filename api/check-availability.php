<?php
/**
 * THE LOVE SHACK — API: Check Calendar Availability
 * GET or POST: checkin, checkout, property_id (optional)
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
$propertyId = (int)($input['property_id'] ?? 1);

if (empty($checkin) || empty($checkout)) {
    json_response([
        'available' => false,
        'error' => 'Please provide both check-in and check-out dates.'
    ], 400);
}

$result = check_dates_availability($checkin, $checkout, $propertyId);

if ($result['available']) {
    json_response($result, 200);
} else {
    json_response($result, 200); // Return 200 with available=false and specific reason
}
