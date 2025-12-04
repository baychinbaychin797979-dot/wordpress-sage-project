<?php
// Minimal theme bootstrap to ensure Composer autoload (Acorn) is available when activated.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize Illuminate Database connection using WordPress DB credentials
function setup_eloquent_connection() {
    global $wpdb;
    
    $capsule = new \Illuminate\Database\Capsule\Manager();
    
    $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';
    $collation = (defined('DB_COLLATION') && DB_COLLATION) ? DB_COLLATION : ($charset === 'utf8' ? 'utf8_general_ci' : 'utf8mb4_unicode_ci');

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => DB_HOST,
        'database'  => DB_NAME,
        'username'  => DB_USER,
        'password'  => DB_PASSWORD,
        'charset'   => $charset,
        'collation' => $collation,
        'prefix'    => '',
    ]);
    
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
}

// You can register theme features here if needed.