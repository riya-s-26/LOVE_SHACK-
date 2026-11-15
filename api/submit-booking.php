<?php
/**
 * THE LOVE SHACK — API: Submit Booking Request
 * POST: checkin, checkout, adults, children, first_name, last_name, email, phone, country, notes, services
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'error' => 'Method not allowed. Use POST.'], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$result = create_booking_request($input);

if ($result['success']) {
    json_response($result, 201);
} else {
    json_response($result, 400);
}
