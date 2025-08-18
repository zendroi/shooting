<?php
/**
 * Laravel Hosting Setup Script
 * Run this file in your browser to setup Laravel for shared hosting
 */

// Check if .env exists
if (!file_exists('.env')) {
    // Create .env from example
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "✓ Created .env from .env.example<br>";
    } else {
        // Create basic .env
        $envContent = "APP_NAME=Laravel\n";
        $envContent .= "APP_ENV=production\n";
        $envContent .= "APP_KEY=\n";
        $envContent .= "APP_DEBUG=false\n";
        $envContent .= "APP_URL=" . (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "\n";
        $envContent .= "LOG_CHANNEL=stack\n";
        $envContent .= "LOG_LEVEL=error\n";
        $envContent .= "DB_CONNECTION=sqlite\n";
        $envContent .= "DB_DATABASE=database/database.sqlite\n";
        $envContent .= "BROADCAST_DRIVER=log\n";
        $envContent .= "CACHE_DRIVER=file\n";
        $envContent .= "FILESYSTEM_DISK=local\n";
        $envContent .= "QUEUE_CONNECTION=sync\n";
        $envContent .= "SESSION_DRIVER=file\n";
        $envContent .= "SESSION_LIFETIME=120\n";
        
        file_put_contents('.env', $envContent);
        echo "✓ Created basic .env file<br>";
    }
}

// Check vendor directory
if (!is_dir('vendor')) {
    echo "❌ Vendor directory not found. Please run: composer install<br>";
    echo "If you don't have SSH access, upload the vendor folder from your local machine.<br>";
    exit;
}

// Create necessary directories
$directories = [
    'storage/app/public',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
    'database'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✓ Created directory: $dir<br>";
    }
}

// Set permissions
$writableDirs = [
    'storage',
    'bootstrap/cache'
];

foreach ($writableDirs as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0755);
        echo "✓ Set permissions for: $dir<br>";
    }
}

// Create SQLite database if using SQLite
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'DB_CONNECTION=sqlite') !== false) {
        if (!file_exists('database/database.sqlite')) {
            touch('database/database.sqlite');
            chmod('database/database.sqlite', 0644);
            echo "✓ Created SQLite database<br>";
        }
    }
}

// Generate app key if not set
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'APP_KEY=') !== false && strpos($envContent, 'APP_KEY=base64:') === false) {
        $key = 'base64:' . base64_encode(random_bytes(32));
        $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);
        file_put_contents('.env', $envContent);
        echo "✓ Generated APP_KEY<br>";
    }
}

echo "<br><strong>Setup completed!</strong><br>";
echo "You can now delete this file (setup-hosting.php) for security.<br>";
echo "<a href='public/'>Go to your Laravel application</a>";
?> 