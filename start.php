<?php

// Get port from environment variable
$port = (int) ($_ENV['PORT'] ?? 8000);

// Ensure port is valid
if ($port < 1 || $port > 65535) {
    $port = 8000;
}

echo "Starting Laravel on port $port...\n";

// Clear config cache
passthru('php artisan config:clear');

// Use exec instead of passthru to avoid Laravel ServeCommand issues
// Build the command string properly to avoid type issues
$command = "php artisan serve --host=0.0.0.0 --port=" . $port;

echo "Executing: $command\n";

// Execute the command
exec($command); 