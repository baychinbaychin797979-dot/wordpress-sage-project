<?php
// Minimal theme bootstrap to ensure Composer autoload (Acorn) is available when activated.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Import Eloquent model (bắt buộc phải có)
use App\Models\MatchModel;

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

// Hàm đếm trận trực tiếp
function count_live_matches() {
    return MatchModel::whereIn('status_id', [2,3,4,5,7])->count();
}