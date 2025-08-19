<?php

/**
 * Custom Laravel Server Script
 * This bypasses Laravel's ServeCommand to avoid type conversion issues
 */

// Get port from environment variable
$port = (int) ($_ENV['PORT'] ?? 8000);

// Ensure port is valid
if ($port < 1 || $port > 65535) {
    $port = 8000;
}

echo "Starting Laravel on port $port...\n";

// Set the document root to the public directory
$documentRoot = __DIR__ . '/public';

// Build the server command
$command = "php -S 0.0.0.0:$port -t $documentRoot";

echo "Executing: $command\n";

// Start the PHP built-in server
passthru($command); 