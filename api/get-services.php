<?php
/**
 * THE LOVE SHACK — API: Retrieve Additional Services
 * GET: category (optional)
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$category = !empty($_GET['category']) ? trim((string)$_GET['category']) : null;
$services = get_additional_services($category);

// Also provide grouped by category
$grouped = [];
foreach ($services as $svc) {
    $cat = $svc['category'] ?? 'Other Extras';
    $grouped[$cat][] = $svc;
}

json_response([
    'success' => true,
    'services' => $services,
    'grouped' => $grouped
], 200);
