<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Database Connection Provider (PDO with Prepared Statements)
 */

declare(strict_types=1);

function get_db_connection(): ?PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    // Check if custom config exists, else fallback to standard local environment defaults
    $configFile = __DIR__ . '/config.php';
    if (file_exists($configFile)) {
        $config = require $configFile;
        $host     = $config['db']['host'] ?? 'localhost';
        $port     = $config['db']['port'] ?? 3306;
        $dbname   = $config['db']['dbname'] ?? 'love_shack';
        $username = $config['db']['username'] ?? 'root';
        $password = $config['db']['password'] ?? '';
        $charset  = $config['db']['charset'] ?? 'utf8mb4';
    } else {
        // Standard XAMPP / local development defaults
        $host     = getenv('DB_HOST') ?: 'localhost';
        $port     = getenv('DB_PORT') ?: 3306;
        $dbname   = getenv('DB_NAME') ?: 'love_shack';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';
        $charset  = 'utf8mb4';
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5,
    ];

    try {
        $pdo = new PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Log error securely without exposing raw credentials in web output
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}

function is_db_connected(): bool {
    return get_db_connection() !== null;
}
