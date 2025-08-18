<?php
/**
 * Railway Environment Setup Script
 * This script sets all required environment variables to prevent Laravel warnings
 */

// Get current environment variables
$currentEnv = file_exists('.env') ? file_get_contents('.env') : '';

// Define all required environment variables with default values
$envVars = [
    // Basic Laravel
    'APP_NAME' => 'Laravel',
    'APP_ENV' => 'production',
    'APP_KEY' => '',
    'APP_DEBUG' => 'false',
    'APP_URL' => '',
    'APP_LOCALE' => 'en',
    'APP_FALLBACK_LOCALE' => 'en',
    'APP_FAKER_LOCALE' => 'en_US',
    
    // Logging
    'LOG_CHANNEL' => 'stack',
    'LOG_DEPRECATIONS_CHANNEL' => 'null',
    'LOG_LEVEL' => 'error',
    'LOG_SLACK_WEBHOOK_URL' => '',
    'PAPERTRAIL_URL' => '',
    'PAPERTRAIL_PORT' => '',
    'LOG_STDERR_FORMATTER' => '',
    
    // Database
    'DB_CONNECTION' => 'sqlite',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'database/database.sqlite',
    'DB_USERNAME' => '',
    'DB_PASSWORD' => '',
    'DB_QUEUE_CONNECTION' => 'sync',
    
    // Mail
    'MAIL_MAILER' => 'log',
    'MAIL_SCHEME' => '',
    'MAIL_URL' => '',
    'MAIL_HOST' => '127.0.0.1',
    'MAIL_PORT' => '2525',
    'MAIL_USERNAME' => '',
    'MAIL_PASSWORD' => '',
    'MAIL_ENCRYPTION' => '',
    'MAIL_FROM_ADDRESS' => 'hello@example.com',
    'MAIL_FROM_NAME' => 'Laravel',
    'MAIL_LOG_CHANNEL' => '',
    'POSTMARK_MESSAGE_STREAM_ID' => '',
    'POSTMARK_TOKEN' => '',
    'RESEND_KEY' => '',
    
    // AWS/S3
    'AWS_ACCESS_KEY_ID' => '',
    'AWS_SECRET_ACCESS_KEY' => '',
    'AWS_DEFAULT_REGION' => 'us-east-1',
    'AWS_BUCKET' => '',
    'AWS_URL' => '',
    'AWS_ENDPOINT' => '',
    'AWS_USE_PATH_STYLE_ENDPOINT' => 'false',
    
    // Queue
    'QUEUE_CONNECTION' => 'sync',
    'SQS_SUFFIX' => '',
    
    // Session
    'SESSION_DRIVER' => 'file',
    'SESSION_LIFETIME' => '120',
    'SESSION_CONNECTION' => '',
    'SESSION_STORE' => '',
    'SESSION_DOMAIN' => '',
    'SESSION_SECURE_COOKIE' => '',
    
    // Cache & Filesystem
    'CACHE_DRIVER' => 'file',
    'FILESYSTEM_DISK' => 'local',
    'BROADCAST_DRIVER' => 'log',
    
    // Slack
    'SLACK_BOT_USER_OAUTH_TOKEN' => '',
    'SLACK_BOT_USER_DEFAULT_CHANNEL' => '',
    
    // Vite
    'VITE_APP_NAME' => 'Laravel',
    'VITE_PUSHER_APP_KEY' => '',
    'VITE_PUSHER_HOST' => '',
    'VITE_PUSHER_PORT' => '',
    'VITE_PUSHER_SCHEME' => '',
    'VITE_PUSHER_APP_CLUSTER' => '',
];

// Update APP_URL if not set
if (empty($envVars['APP_URL'])) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $envVars['APP_URL'] = $protocol . $host;
}

// Generate APP_KEY if not set
if (empty($envVars['APP_KEY'])) {
    $envVars['APP_KEY'] = 'base64:' . base64_encode(random_bytes(32));
}

// Build .env content
$envContent = '';
foreach ($envVars as $key => $value) {
    $envContent .= $key . '=' . $value . "\n";
}

// Write to .env file
file_put_contents('.env', $envContent);

echo "✓ Railway environment variables configured successfully!\n";
echo "✓ APP_KEY generated: " . substr($envVars['APP_KEY'], 0, 20) . "...\n";
echo "✓ APP_URL set to: " . $envVars['APP_URL'] . "\n";
echo "✓ Database set to SQLite\n";
echo "✓ Mail set to log driver\n";
echo "✓ All AWS/Slack/Postmark variables set to empty (no warnings)\n";

// Create database directory and file if using SQLite
if ($envVars['DB_CONNECTION'] === 'sqlite') {
    if (!is_dir('database')) {
        mkdir('database', 0755, true);
    }
    if (!file_exists('database/database.sqlite')) {
        touch('database/database.sqlite');
        chmod('database/database.sqlite', 0644);
        echo "✓ SQLite database created\n";
    }
}

echo "\n🚀 Your Laravel app is ready for Railway deployment!\n";
?> 