<?php
/**
 * THE LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Database & Environment Configuration Template
 * 
 * Instructions:
 * 1. Copy this file to 'config.php' in the same directory (or configure config/database.php).
 * 2. Update with your MySQL host, database name, username, and password.
 * 3. Never commit production passwords or sensitive credentials to GitHub.
 */

return [
    'db' => [
        'host'     => 'localhost',
        'port'     => 3306,
        'dbname'   => 'love_shack',
        'username' => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
    ],
    'app' => [
        'env'      => 'development', // 'development' or 'production'
        'base_url' => '',            // Leave empty for auto-detection or set e.g. 'http://localhost/LOVE-SHACK'
        'site_name'=> 'The Love Shack',
    ]
];
