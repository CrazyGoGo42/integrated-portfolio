<?php

// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'portfolio_db');
define('DB_USER', getenv('DB_USER') ?: 'portfolio_user');
define('DB_PASS', getenv('DB_PASS') ?: 'portfolio_pass');
define('DB_PORT', getenv('DB_PORT') ?: '3306');

// API configuration
define('API_VERSION', '1.0');
define('UPLOAD_PATH', './uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// Security settings
define('ENABLE_CORS', true);
define('ADMIN_TOKEN', getenv('ADMIN_TOKEN') ?: 'your_secure_admin_token_change_this');

// Image processing settings
define('THUMB_WIDTH', 300);
define('THUMB_HEIGHT', 200);
define('MAX_IMAGE_WIDTH', 1920);
define('MAX_IMAGE_HEIGHT', 1080);

// Error reporting (disable in production)
if (getenv('ENVIRONMENT') === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
