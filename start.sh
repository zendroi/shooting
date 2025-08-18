#!/bin/bash

# Set default port if not provided
PORT=${PORT:-8000}

# Wait for a moment to ensure everything is ready
sleep 2

# Clear any cached config
php artisan config:clear

# Start Laravel server
echo "Starting Laravel on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT 