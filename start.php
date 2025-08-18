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

// Start Laravel server
passthru("php artisan serve --host=0.0.0.0 --port=$port"); 